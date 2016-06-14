<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(BASEPATH.'database/drivers/pdo/subdrivers/pdo_mysql_driver.php');

class MY_DB_pdo_driver extends CI_DB_pdo_mysql_driver {
        protected $qb_lock_in_share_mode = FALSE;
        protected $qb_for_update         = FALSE;

        public function __construct($params){
		parent::__construct($params);
        }

        public function set_lock_in_share_mode() {
            if ($this->qb_for_update) return $this;
            $this->qb_lock_in_share_mode = true;
            return $this;
        }

        public function set_for_update() {
            if ($this->qb_lock_in_share_mode) return $this;
            $this->qb_for_update = true;
            return $this;
        }

        // --------------------------------------------------------------------

	/**
	 * Compile the SELECT statement
	 *
	 * Generates a query string based on which functions were used.
	 * Should not be called directly.
	 *
	 * @param	bool	$select_override
	 * @return	string
	 */
	protected function _compile_select($select_override = FALSE)
	{
            // Combine any cached components with the current statements
            $this->_merge_cache();

            // Write the "select" portion of the query
            if ($select_override !== FALSE)
            {
                    $sql = $select_override;
            }
            else
            {
                    $sql = ( ! $this->qb_distinct) ? 'SELECT ' : 'SELECT DISTINCT ';

                    if (count($this->qb_select) === 0)
                    {
                            $sql .= '*';
                    }
                    else
                    {
                            // Cycle through the "select" portion of the query and prep each column name.
                            // The reason we protect identifiers here rather then in the select() function
                            // is because until the user calls the from() function we don't know if there are aliases
                            foreach ($this->qb_select as $key => $val)
                            {
                                    $no_escape = isset($this->qb_no_escape[$key]) ? $this->qb_no_escape[$key] : NULL;
                                    $this->qb_select[$key] = $this->protect_identifiers($val, FALSE, $no_escape);
                            }

                            $sql .= implode(', ', $this->qb_select);
                    }
            }

            // Write the "FROM" portion of the query
            if (count($this->qb_from) > 0)
            {
                    $sql .= "\nFROM ".$this->_from_tables();
            }

            // Write the "JOIN" portion of the query
            if (count($this->qb_join) > 0)
            {
                    $sql .= "\n".implode("\n", $this->qb_join);
            }

            $sql .= $this->_compile_wh('qb_where')
                    .$this->_compile_group_by()
                    .$this->_compile_wh('qb_having')
                    .$this->_compile_order_by(); // ORDER BY

            // LIMIT
            if ($this->qb_limit)
            {
                    $sql = $this->_limit($sql."\n");
            }

            // ----------------------------------------------------------------

            // Write the "LOCK IN SHARE MODE" portion of the query

            if ($this->qb_lock_in_share_mode)
            {
                    $sql .= "\nLOCK IN SHARE MODE";
            }

            // ----------------------------------------------------------------

            // Write the "LOCK IN SHARE MODE" portion of the query

            if ($this->qb_for_update)
            {
                    $sql .= "\nFOR UPDATE";
            }

            return $sql;
	}

        // --------------------------------------------------------------------

	/**
	 * Resets the query builder values.  Called by the get() function
	 *
	 * @return	void
	 */
	protected function _reset_select()
	{
		$this->_reset_run(array(
			'qb_select'		=> array(),
			'qb_from'		=> array(),
			'qb_join'		=> array(),
			'qb_where'		=> array(),
			'qb_groupby'		=> array(),
			'qb_having'		=> array(),
			'qb_orderby'		=> array(),
			'qb_aliased_tables'	=> array(),
			'qb_no_escape'		=> array(),
			'qb_distinct'		=> FALSE,
			'qb_limit'		=> FALSE,
			'qb_offset'		=> FALSE,
                        'qb_lock_in_share_mode' => FALSE,
                        'qb_for_update'         => FALSE
		));
	}

	// --------------------------------------------------------------------

	/**
	 * Resets the query builder "write" values.
	 *
	 * Called by the insert() update() insert_batch() update_batch() and delete() functions
	 *
	 * @return	void
	 */
	protected function _reset_write()
	{
		$this->_reset_run(array(
			'qb_set'	=> array(),
			'qb_from'	=> array(),
			'qb_join'	=> array(),
			'qb_where'	=> array(),
			'qb_orderby'	=> array(),
			'qb_keys'	=> array(),
			'qb_limit'	=> FALSE,
                        'qb_lock_in_share_mode' => FALSE,
                        'qb_for_update'         => FALSE
		));
	}
}

/* End of file DB_active_rec.php */
/* Location: ./system/database/DB_active_rec.php */