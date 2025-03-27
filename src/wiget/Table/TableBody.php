<?php
namespace Kanneh\PhpDashboard\Wiget\Table;

class TableBody{
    public $table;
    public static function create(array $data,PSSLTable $table): TableBody{
        $tb = new TableBody();
        $tb->table = $table;
        print_r("<tbody>");
        foreach($data as $row){
            print_r("<tr>");
            foreach($table->header->cells as $cell){
                $value = $row[$cell->name];
                if($cell->visible){
                    print_r("<td>{$value}</td>");
                }
            }
            print_r("</tr>");
        }
        print_r("</tbody>");
        return $tb;

    }
}