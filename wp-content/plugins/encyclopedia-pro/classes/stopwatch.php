<?php Namespace WordPress\Plugin\Encyclopedia;

use StdClass;

abstract class Stopwatch {
  private static
    $arr_watches = Array(); # contains all stop watches

  public static function create($watch_name){
    $watch = new StdClass;
    $watch->name = $watch_name;
    static::$arr_watches[$watch_name] = $watch;
    return $watch;
  }

  public static function delete($watch_name){
    unset(static::$arr_watches[$watch_name]);
  }

  public static function retrieve($watch_name){
    if (isSet(static::$arr_watches[$watch_name]))
      return static::$arr_watches[$watch_name];
    else
      return False;
  }

  public static function start($watch_name){
    $watch = static::create($watch_name);
    $watch->start = Microtime(True);
  }

  public static function stop($watch_name){
    if ($watch = static::retrieve($watch_name)){
      $watch->end = Microtime(True);
      $watch->duration = $watch->end - $watch->start;
    }
  }

  public static function all(){
    return static::$arr_watches;
  }

  public static function printAll(){
    ?>
    <table>
      <?php foreach (static::$arr_watches as $watch): ?>
      <tr>
        <th><?php echo $watch->name ?></th>
        <td><?php echo round($watch->duration * 1000) ?>ms</td>
      </tr>
      <?php endforeach ?>
    </table>
    <?php
  }

}
