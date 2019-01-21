<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class Capabilities {

  static function resetCapabilities(){
    if (!function_exists('populate_roles')){
      require_once(ABSPATH . 'wp-admin/includes/schema.php');
    }

    populate_Roles();
  }

  static function getPostTypeCapabilities($capability_type = Null){
    if (empty($capability_type))
      $capability_type = Post_Type::advanced_capability_type;

    $args = Array(
      'capability_type' => $capability_type,
      'capabilities' => Array(),
      'map_meta_cap' => True
    );
    setType($args, 'Object');

    $capabilities = get_Post_Type_Capabilities($args);

    return $capabilities;
  }

  static function getUserRoles(){
    global $wp_roles;
    $arr_roles = $wp_roles->roles;
    return $arr_roles;
  }

  static function hasCap($role, $capability){
    $arr_roles = static::getUserRoles();
    return !empty($arr_roles[$role]['capabilities'][$capability]);
  }

  static function setCap($role, $capability, $state = True){
    $role = get_Role($role);

    if (empty($role))
      return False;

    return $state ? $role->add_Cap($capability) : $role->remove_Cap($capability);
  }

  static function addCap($role, $capability){
    return static::setCap($role, $capability, True);
  }

  static function removeCap($role, $capability){
    return static::setCap($role, $capability, False);
  }

}
