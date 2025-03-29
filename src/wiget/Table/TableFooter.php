<?php
namespace Kanneh\PhpDashboard\Wiget\Table;

class TableFooter{
    public $table;

    public static function create($config, $table): self{
        $tf = new TableFooter();
        $tf->table = $table;
        return $tf;
    }
}