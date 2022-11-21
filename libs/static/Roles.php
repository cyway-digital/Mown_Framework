<?php

class Roles {

    public static function getRightDefinition($role) {
        $function = $role."_roleDef";
        return Self::$function();
    }

    public static function getRight($role,$def) {
        $function = $role."_roleDef";

        if (isset(Self::$function()[$def]) && Self::$function()[$def] === 1) {
            return true;
        }

        return false;
    }

    public static function getMyRight($def) {
        $function = Session::get('role')."_roleDef";

        if (isset(Self::$function()[$def]) && Self::$function()[$def] === 1) {
            return true;
        }

        return false;
    }

    private static function ADMIN_roleDef() {
        return array(
            'settings_info' => 1,
            'main_menu' => 1,
            'admin_menu' => 1,
            'manage_customers' => 1,
            'manage_users' => 1,
            'manage_system_settings' => 1,
        );
    }

    private function USER_roleDef() {
        return array(
        );
    }
}