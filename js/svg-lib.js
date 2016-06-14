(function($) {
    $(function() {
var map_img = $('#map .img'),
    map_svg = $('#map svg'),
    pathes = [],
    polylines = [];
//get polyline data in to object
        function polylineToArray( _path ){
            var points = $(_path).attr('points'),                
                obj = [],
                ar = null,
                o_ar = null;
            
            ar = points.split(' ');
            
            for( var i = 0; i < ar.length; i++ ){
                if( ar[i] == '' ) continue;                
                o_ar = ar[i].split(',');
                
                obj.push( parseFloat(o_ar[0]) );
                obj.push( parseFloat(o_ar[1]) );
            }
            return { obj: $(_path),path: obj };
        }        
        
        //get path data in to object
        function pathToObj( _path ){
            var d = $(_path).attr('d'),                
                obj = [],
                ar = null;
        
            d = d.replace(/ /g,'');
            
            var d_len = d.length;
            for( var a = 0, o = null, str = ''; a < d_len; a++ ){
                
                if( !(d[a] >= 'A' && d[a] <= 'Z'|| d[a] >= 'a' && d[a] <= 'z' ) && a+1 < d_len
                  ){
                    if((d[a] === '-' || d[a] === ',') && str.length !== 0){
//                        console.log( str.length );
                        o.path.push( parseFloat(str) );
                        str = '';
                    }
                    
                    if( d[a] !== ',' ) str += d[a];
                    
                    continue;
                }
                    
                if( a === 0 ){
                    o = new Object();
                    o.op = d[a];
                    o.path = [];
                    continue;
                }
                if( a+1 == d_len ) str += d[a];

                
                o.path.push( parseFloat(str) );
                obj.push( o );
                
//                console.log( str );
//                console.log( d[a] );
                o = new Object();
                o.op = d[a];
                o.path = [];
                str = '';
            }
            
            return { obj: $(_path),path: obj };
        }        
        
        //convert object to string path
        function pathAssemble( _pathes ){
            var str = [];
            for(var i in _pathes){
                str.push( _pathes[i].op );
                for( var a = 0; a < _pathes[i].path.length; a++ ){
                    if( _pathes[i].path[a] >= 0 && a > 0) str.push( ',' );
                    str.push( _pathes[i].path[a] );
                }                
            }
            return str.join('');
        }
        
        //calculate all the points of the polyline
        function polylineCalculate( _obj ){           
            if( _obj.obj == undefined ||
                _obj.obj != undefined && _obj.obj.length == 0 ) return false;
            
            var new_obj = [];
            
            for( var i = 0; i <_obj.path.length; i += 2 )
                new_obj.push( (Math.round(_obj.path[i]*map.scale * 100) / 100)+','+(Math.round(_obj.path[i+1]*map.scale * 100) / 100) );

                       
            var new_path = new_obj.join(' ');            
            $( _obj.obj ).attr('points',new_path);
        }
        //calculate all the points of the object
        function pathCalculate( _obj ){           
//            console.log( 'pathCalculate' );
            if( _obj.obj == undefined ||
                _obj.obj != undefined && _obj.obj.length == 0 ) return false;
            
            var new_obj = [], o = null;
//            console.log( 'pathCalculate' );
            for( var i in _obj.path ){
                o = new Object();
                o.op = _obj.path[i].op;
                o.path = [];
                
//                console.log( '--next--' );
//                console.log( '--len--' + _obj.path[i].path.length );
                
                for( var a = 0; a<_obj.path[i].path.length; a++){
                    o.path[a] = Math.round(_obj.path[i].path[a]*map.scale*1000)/1000;
//                    console.log( _obj.path[i].path[a]+' + '+o.path[a] );
                }
                                
                new_obj.push( o );
            }
//            console.log('new_obj');
//            console.log(new_obj);
            var new_path = pathAssemble( new_obj );
//            console.log(new_path);
            $( _obj.obj ).attr('d',new_path);
        }
        
        //on load - loading all the pathes to the paths variable
        function svgLoad(){               
            
            $('#map polyline').each(function(){
                polylines.push( polylineToArray( $(this) ) );
            });
            
            $('#map path').each(function(){
                pathes.push( pathToObj( $(this) ) );
            });
            
//            pathes.push( pathToObj( $('#test') ) );            
                    
//            console.log( 'pathes' );                        
//            console.log( pathes );                        
//            console.log( polylines );                        
        }
        
        //recalculate all svg path
        function svgUpdate(){
            for(var o in polylines){
                polylineCalculate( polylines[o] );
            };
            for(var o in pathes){
//                console.log( 'svgUpdate-3' );
                pathCalculate( pathes[o] );
            };
        }
    });    
})(jQuery);