<?php
 /* Class Extensible  
 *   Add function to object at runt-time
 * 
 */
 /* Example
   class Bar extends Foo { }
Foo::addMethod("customMethod", function($object) {
    return "Foo";
});
 
$bar = new Bar();
$result = $bar->customMethod();
// $result contains the string "Foo"
 */
 abstract class Extensible {
    public function __call($name, $args)
    {
        return self::methodDispatcher($this, $name, $args);
    }

    private static $methodTable = array();

    public static function methodDispatcher($instance, $name, $args)
    {
        $class = get_class($instance);
        $table =& self::$methodTable;

        $table =& self::$methodTable;
        $class = get_class($instance);
        do
        {
            if (array_key_exists($class, $table) && array_key_exists($name, $table[$class]))
                break;

            $class = get_parent_class($class);
        }
        while ($class !== false);

        if ($class === false)
            throw new NException("Method not found");

        $func = $table[$class][$name];
        array_unshift($args, $instance);

        return call_user_func_array($func, $args);
    }

    public static function addMethod($methodName, $method)
    {
        $class = get_called_class();

        $table =& self::$methodTable;
        if (!array_key_exists($class, $table))
        {
            $table[$class] = array();
        }

        $table[$class][$methodName] = $method;
    }
}

?>