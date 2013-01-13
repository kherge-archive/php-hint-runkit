<?

/**
 * runkit_import() flag indicating that normal functions should be imported
 * from the specified file.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_FUNCTIONS', 1);

 /**
  * runkit_import() flag indicating that class methods should be imported
  * from the specified file.
  *
  * @var integer
  */
define('RUNKIT_IMPORT_CLASS_METHODS', 2);

/**
 * runkit_import() flag indicating that class constants should be imported
 * from the specified file. Note that this flag is only meaningful in PHP
 * versions 5.1.0 and above.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_CLASS_CONSTS', 4);

/**
 * runkit_import() flag indicating that class standard properties should be
 * imported from the specified file.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_CLASS_PROPS', 8);

/**
 * runkit_import() flag indicating that class static properties should be
 * imported from the specified file.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_CLASS_STATIC_PROPS', 10);

/**
 * runkit_import() flag representing a bitwise OR of the RUNKIT_IMPORT_CLASS_*
 * constants.
 *
 * @var integer
 */
define('RUNKIT_IMPORT_CLASSES', (
    RUNKIT_IMPORT_CLASS_METHODS
        | RUNKIT_IMPORT_CLASS_CONSTS
        | RUNKIT_IMPORT_CLASS_PROPS
        | RUNKIT_IMPORT_CLASS_STATIC_PROPS
));

 /**
  * runkit_import() flag indicating that if any of the imported functions,
  * methods, constants, or properties already exist, they should be replaced
  * with the new definitions. If this flag is not set, then any imported
  * definitions which already exist will be discarded.
  *
  * @var integer
  */
define('RUNKIT_IMPORT_OVERRIDE', 20);

/**
 * PHP 5 specific flag to runkit_method_add()
 *
 * @var integer
 */
define('RUNKIT_ACC_PUBLIC', 256);

/**
 * PHP 5 specific flag to runkit_method_add()
 *
 * @var integer
 */
define('RUNKIT_ACC_PROTECTED', 512);

/**
 * PHP 5 specific flag to runkit_method_add()
 *
 * @var integer
 */
define('RUNKIT_ACC_PRIVATE', 1024);

 /**
  * PHP 5 specific flag to runkit_method_add()
  *
  * @var integer
  */
define('RUNKIT_ACC_STATIC', 1);

/**
 * PHP 5 specific flag to runkit_method_add()
 *
 * @var integer
 */
define('RUNKIT_ACC_RETURN_REFERENCE', 0x4000000);

/**
 * PHP 5 specific flag to classkit_method_add() Only defined when classkit
 * compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_ACC_PUBLIC', 256);

/**
 * PHP 5 specific flag to classkit_method_add() Only defined when classkit
 * compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_ACC_PROTECTED', 512);

/**
 * PHP 5 specific flag to classkit_method_add() Only defined when classkit
 * compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_ACC_PRIVATE', 1024);

/**
 * PHP 5 specific flag to classkit_import() Only defined when classkit
 * compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_AGGREGATE_OVERRIDE', 32);

/**
 * Defined to the current version of the runkit package.
 *
 * @var integer
 */
define('RUNKIT_VERSION', '1.0.4-dev');

/**
 * Defined to the current version of the runkit package. Only defined when
 * classkit compatibility is enabled.
 *
 * @var integer
 */
define('CLASSKIT_VERSION', '1.0.4-dev');

/**
 * Instantiating the Runkit_Sandbox class creates a new thread with its own
 * scope and program stack. Using a set of options passed to the constructor,
 * this environment may be restricted to a subset of what the primary
 * interpreter can do and provide a safer environment for executing user
 * supplied code.
 *
 * <blockquote>
 *   Note: Sandbox support (required for runkit_lint(), runkit_lint_file(),
 *   and the Runkit_Sandbox class) is only available as of PHP 5.1.0 or
 *   specially patched versions of PHP 5.0, and requires that thread safety
 *   be enabled. See the README file included in the runkit package for more
 *   information.
 * </blockquote>
 *
 * <h2>Accessing Variables</h2>
 *
 * All variables in the global scope of the sandbox environment are accessible
 * as properties of the sandbox object. The first thing to note is that because
 * of the way memory between these two threads is managed, object and resource
 * variables can not currently be exchanged between interpreters. Additionally,
 * all arrays are deep copied and any references will be lost. This also means
 * that references between interpreters are not possible.
 *
 * <code>
 *   <?php
 *   $sandbox = new Runkit_Sandbox();
 *
 *   $sandbox->foo = 'bar';
 *   $sandbox->eval('echo "$foo\n"; $bar = $foo . "baz";');
 *   echo "{$sandbox->bar}\n";
 *   if (isset($sandbox->foo)) unset($sandbox->foo);
 *   $sandbox->eval('var_dump(isset($foo));');
 *   ?>
 * </code>
 *
 * The above example will output:
 *
 * <blockquote>
 *   bar
 *   barbaz
 *   bool(false)
 * </blockquote>
 *
 * <h2>Calling PHP Functions</h2>
 *
 * Any function defined within the sandbox may be called as a method on the
 * sandbox object. This also includes a few pseudo-function language
 * constructs: eval(), include, include_once, require, require_once, echo,
 * print, die(), and exit().
 *
 * <code>
 *   <?php
 *   $sandbox = new Runkit_Sandbox();
 *
 *   echo $sandbox->str_replace('a','f','abc');
 *   ?>
 * </code>
 *
 * The above example will output:
 *
 * <blockquote>fbc</blockquote>
 *
 * When passing arguments to a sandbox function, the arguments are taken from
 * the outer instance of PHP. If you wish to pass arguments from the sandbox's
 * scope, be sure to access them as properties of the sandbox object as
 * illustrated above.
 *
 * <code>
 *   <?php
 *   $sandbox = new Runkit_Sandbox();
 *
 *   $foo = 'bar';
 *   $sandbox->foo = 'baz';
 *   echo $sandbox->str_replace('a',$foo,'a');
 *   echo $sandbox->str_replace('a',$sandbox->foo,'a');
 *   ?>
 * </code>
 *
 * The above example will output:
 *
 * <blockquote>
 *   bar
 *   baz
 * </blockquote>
 *
 * <h2>Changing Sandbox Settings</h2>
 *
 * As of runkit version 0.5, certain Sandbox settings may be modified on the
 * fly using ArrayAccess syntax. Some settings, such as active are read-only
 * and meant to provide status information. Other settings, such as
 * output_handler may be set and read much like a normal array offset. Future
 * settings may be write-only, however no such settings currently exist.
 *
 * <table>
 *   <thead>
 *     <tr>
 *       <th>Setting</th>
 *       <th>Type</th>
 *       <th>Purpose</th>
 *       <th>Default</th>
 *     </tr>
 *   </thead>
 *   <tbody>
 *     <tr>
 *       <th>active</th>
 *       <td>Boolean (Read Only)</td>
 *       <td>TRUE if the Sandbox is still in a usable state, FALSE if the
 *           request is in bailout due to a call to die(), exit(), or because
 *           of a fatal error condition.</td>
 *       <td>TRUE (Initial)</td>
 *     </tr>
 *     <tr>
 *       <th>output_handler</th>
 *       <td>Callback</td>
 *       <td>When set to a valid callback, all output generated by the Sandbox
 *           instance will be processed through the named function. Sandbox
 *           output handlers follow the same calling conventions as the
 *           system-wide output handler.</td>
 *       <td>None</td>
 *     </tr>
 *     <tr>
 *       <th>parent_access</th>
 *       <td>Boolean</td>
 *       <td>May the sandbox use instances of the Runkit_Sandbox_Parent class?
 *           Must be enabled for other Runkit_Sandbox_Parent related settings to
 *           work.</td>
 *       <td>FALSE</td>
 *     </tr>
 *     <tr>
 *       <th>parent_read</th>
 *       <td>Boolean</td>
 *       <td>May the sandbox read variables in its parent's context?</td>
 *       <td>FALSE</td>
 *     </tr>
 *     <tr>
 *       <th>parent_write</th>
 *       <td>Boolean</td>
 *       <td>May the sandbox modify variables in its parent's context?</td>
 *       <td>FALSE</td>
 *     </tr>
 *     <tr>
 *       <th>parent_eval</th>
 *       <td>Boolean</td>
 *       <td>May the sandbox evaluate arbitrary code in its parent's context?
 *           DANGEROUS</td>
 *       <td>FALSE</td>
 *     </tr>
 *     <tr>
 *       <th>parent_include</th>
 *       <td>Boolean</td>
 *       <td>May the sandbox include php code files in its parent's context?
 *           DANGEROUS</td>
 *       <td>FALSE</td>
 *     </tr>
 *     <tr>
 *       <th>parent_echo</th>
 *       <td>Boolean</td>
 *       <td>May the sandbox echo data in its parent's context effectively
 *           bypassing its own output_handler?</td>
 *       <td>FALSE</td>
 *     </tr>
 *     <tr>
 *       <th>parent_call</th>
 *       <td>Boolean</td>
 *       <td>May the sandbox call functions in its parent's context?</td>
 *       <td>FALSE</td>
 *     </tr>
 *     <tr>
 *       <th>parent_die</th>
 *       <td>Boolean</td>
 *       <td>May the sandbox kill its own parent? (And thus itself)</td>
 *       <td>FALSE</td>
 *     </tr>
 *     <tr>
 *       <th>parent_scope</th>
 *       <td>Integer</td>
 *       <td>What scope will parental property access look at? 0 == Global
 *           scope, 1 == Calling scope, 2 == Scope preceeding calling scope,
 *           3 == The scope before that, etc..., etc...</td>
 *       <td>0 (Global)</td>
 *     </tr>
 *     <tr>
 *       <th>parent_scope</th>
 *       <td>String</td>
 *       <td>When parent_scope is set to a string value, it refers to a named
 *           array variable in the global scope. If the named variable does not
 *           exist at the time of access it will be created as an empty array.
 *           If the variable exists but it not an array, a dummy array will be
 *           created containing a reference to the named global variable.</td>
 *       <td></td>
 *     </tr>
 *   </tbody>
 * </table>
 */
class Runkit_Sandbox
{
    /**
     * <code>
     *   <?php
     *    $options = array(
     *    'safe_mode'=>true,
     *    'open_basedir'=>'/var/www/users/jdoe/',
     *    'allow_url_fopen'=>'false',
     *    'disable_functions'=>'exec,shell_exec,passthru,system',
     *    'disable_classes'=>'myAppClass');
     *    $sandbox = new Runkit_Sandbox($options);
     *    // Non-protected ini settings may set normally
     *    $sandbox->ini_set('html_errors',true);
     *    ?>
     * </code>
     *
     * <ul>
     *   <li>
     *     <h3>safe_mode</h3>
     *     <p>If the outer script which is instantiating the Runkit_Sandbox
     *        class is configured with safe_mode = off, then safe_mode may be
     *        turned on for the sandbox environment. This setting can not be
     *        used to disable safe_mode when it's already enabled in the outer
     *        script.</p>
     *   </li>
     *   <li>
     *     <h3>safe_mode_gid</h3>
     *     <p>If the outer script which is instantiating the Runkit_Sandbox
     *        class is configured with safe_mode_gid = on, then safe_mode_gid
     *        may be turned off for the sandbox environment. This setting can
     *        not be used to enable safe_mode_gid when it's already disabled in
     *        the outer script.</p>
     *   </li>
     *   <li>
     *     <h3>safe_mode_include_dir</h3>
     *     <p>If the outer script which is instantiating the Runkit_Sandbox
     *        class is configured with a safe_mode_include_dir, then a new
     *        safe_mode_include_dir may be set for sandbox environments below
     *        the currently defined value. safe_mode_include_dir may also be
     *        cleared to indicate that the bypass feature is disabled. If
     *        safe_mode_include_dir was blank in the outer script, but safe_mode
     *        was not enabled, then any arbitrary safe_mode_include_dir may be
     *        set while turning safe_mode on.</p>
     *   </li>
     *   <li>
     *     <h3>open_basedir</h3>
     *     <p>open_basedir may be set to any path below the current setting of
     *        open_basedir. If open_basedir is not set within the global scope,
     *        then it is assumed to be the root directory and may be set to any
     *        location.</p>
     *   </li>
     *   <li>
     *     <h3>allow_url_fopen</h3>
     *     <p>Like safe_mode, this setting can only be made more restrictive, in
     *        this case by setting it to FALSE when it is previously set to TRUE
     *        </p>
     *   </li>
     *   <li>
     *     <h3>disable_functions</h3>
     *     <p>Comma separated list of functions to disable within the sandbox
     *        sub-interpreter. This list need not contain the names of the
     *        currently disabled functions, they will remain disabled whether
     *        listed here or not.</p>
     *   </li>
     *   <li>
     *     <h3>disable_classes</h3>
     *     <p>Comma separated list of classes to disable within the sandbox
     *        sub-interpreter. This list need not contain the names of the
     *        currently disabled classes, they will remain disabled whether
     *        listed here or not.</p>
     *   </li>
     *   <li>
     *     <h3>runkit.superglobal</h3>
     *     <p>Comma separated list of variables to be treated as superglobals
     *        within the sandbox sub-interpreter. These variables will be used
     *        in addition to any variables defined internally or through the
     *        global runkit.superglobal setting.</p>
     *   </li>
     *   <li>
     *     <h3>runkit.internal_override</h3>
     *     <p>Ini option runkit.internal_override may be disabled (but not
     *        re-enabled) within sandboxes.</p>
     *   </li>
     * </ul>
     *
     * @param array $options options is an associative array containing any
     *                       combination of the special ini options listed
     *                       below.
     */
    public function __construct(array $options = array())
    {
    }
}

/**
 * Instantiating the Runkit_Sandbox_Parent class from within a sandbox
 * environment created from the Runkit_Sandbox class provides some (controlled)
 * means for a sandbox child to access its parent.
 *
 * <blockquote>
 *   Note: Sandbox support (required for runkit_lint(), runkit_lint_file(), and
 *   the Runkit_Sandbox class) is only available as of PHP 5.1.0 or specially
 *   patched versions of PHP 5.0, and requires that thread safety be enabled.
 *   See the README file included in the runkit package for more information.
 * </blockquote>
 *
 * In order for any of the Runkit_Sandbox_Parent features to function. Support
 * must be enabled on a per-sandbox basis by enabling the parent_access flag
 * from the parent's context.
 *
 * <blockquote>
 *   <?php
 *   $sandbox = new Runkit_Sandbox();
 *   $sandbox['parent_access'] = true;
 *   ?>
 * </blockquote>
 *
 * <h2>Accessing the Parent's Variables</h2>
 *
 * Just as with sandbox variable access, a sandbox parent's variables may be
 * read from and written to as properties of the Runkit_Sandbox_Parent class.
 * Read access to parental variables may be enabled with the parent_read setting
 * (in addition to the base parent_access setting). Write access, in turn, is
 * enabled through the parent_write setting.
 *
 * Unlike sandbox child variable access, the variable scope is not limited to
 * globals only. By setting the parent_scope setting to an appropriate integer
 * value, other scopes in the active call stack may be inspected instead. A
 * value of 0 (Default) will direct variable access at the global scope. 1 will
 * point variable access at whatever variable scope was active at the time the
 * current block of sandbox code was executed. Higher values progress back
 * through the functions that called the functions that led to the sandbox
 * executing code that tried to access its own parent's variables.
 *
 * <code>
 *   <?php
 *   $php = new Runkit_Sandbox();
 *   $php['parent_access'] = true;
 *   $php['parent_read'] = true;
 *
 *   $test = "Global";
 *
 *   $php->eval('$PARENT = new Runkit_Sandbox_Parent;');
 *
 *   $php['parent_scope'] = 0;
 *   one();
 *
 *   $php['parent_scope'] = 1;
 *   one();
 *
 *   $php['parent_scope'] = 2;
 *   one();
 *
 *   $php['parent_scope'] = 3;
 *   one();
 *
 *   $php['parent_scope'] = 4;
 *   one();
 *
 *   $php['parent_scope'] = 5;
 *   one();
 *
 *   function one() {
 *   $test = "one()";
 *   two();
 *   }
 *
 *   function two() {
 *   $test = "two()";
 *   three();
 *   }
 *
 *   function three() {
 *   $test = "three()";
 *   $GLOBALS['php']->eval('var_dump($PARENT->test);');
 *   }
 *   ?>
 * </code>
 *
 * The above example will output:
 *
 * <blockquote>
 *   string(6) "Global"
 *   string(7) "three()"
 *   string(5) "two()"
 *   string(5) "one()"
 *   string(6) "Global"
 *   string(6) "Global"
 * </blockquote>
 *
 * <h2>Calling the Parent's Functions</h2>
 *
 * Just as with sandbox access, a sandbox may access its parents functions
 * providing that the proper settings have been enabled. Enabling parent_call
 * will allow the sandbox to call all functions available to the parent scope.
 * Language constructs are each controlled by their own setting: print and echo
 * are enabled with parent_echo. die() and exit() are enabled with parent_die.
 * eval() is enabled with parent_eval while include, include_once, require, and
 * require_once are enabled through parent_include.
 */
class Runkit_Sandbox_Parent
{
    public function __construct()
    {
    }
}

/**
 * Convert a base class to an inherited class, add ancestral methods when
 * appropriate
 *
 * @param string $classname  Name of class to be adopted
 * @param string $parentname Parent class which child class is extending
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_class_adopt($classname, $parentname)
{
}

/**
 * Convert an inherited class to a base class, removes any method whose scope is
 * ancestral
 *
 * @param $classname Name of class to emancipate
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_class_emancipate($classname)
{
}

/**
 * Similar to define(), but allows defining in class definitions as well
 *
 * @param string $constname Name of constant to declare. Either a string to
 *                          indicate a global constant, or classname::constname
 *                          to indicate a class constant.
 * @param mixed  $value     NULL, Bool, Long, Double, String, or Resource value
 *                          to store in the new constant.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_constant_add($constname, $value)
{
}

/**
 * Redefine an already defined constant
 *
 * @param string $constname Constant to redefine. Either string indicating
 *                          global constant, or classname::constname indicating
 *                          class constant.
 * @param mixed  $newvalue  New value to assign to constant.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_constant_redefine($constname, $newvalue)
{
}

/**
 * Remove/Delete an already defined constant
 *
 * @param string $constname Name of constant to remove. Either a string
 *                          indicating a global constant, or classname::constname
 *                          indicating a class constant.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_constant_remove($constname)
{
}

/**
 * Add a new function, similar to create_function()
 *
 * @param string $funcname Name of function to be created
 * @param string $arglist  Comma separated argument list
 * @param string $code     Code making up the function
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_add($funcname, $arglist, $code)
{
}

/**
 * Copy a function to a new function name
 *
 * @param string $funcname   Name of existing function
 * @param string $targetname Name of new function to copy definition to
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_copy($funcname, $targetname)
{
}

/**
 * Replace a function definition with a new implementation
 *
 * @param string $funcname Name of function to redefine
 * @param string $arglist  New list of arguments to be accepted by function
 * @param string $code     New code implementation
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_redefine($funcname, $arglist, $code)
{
}

/**
 * Remove a function definition
 *
 * @param string $funcname Name of function to be deleted
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_remove($funcname)
{
}

/**
 * Change a function's name
 *
 * @param string $funcname Current function name
 * @param string $newname  New function name
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_function_rename($funcname, $newname)
{
}

/**
 * Process a PHP file importing function and class definitions, overwriting
 * where appropriate
 *
 * @param string  $filename Filename to import function and class definitions
 *                          from
 * @param integer $flags    Bitwise OR of the RUNKIT_IMPORT_* family of
 *                          constants.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_import($filename, $flags = RUNKIT_IMPORT_CLASS_METHODS)
{
}

/**
 * Check the PHP syntax of the specified file
 *
 * The runkit_lint_file() function performs a syntax (lint) check on the
 * specified filename testing for scripting errors. This is similar to using
 * php -l from the commandline.
 *
 * <blockquote>
 *   Note: Sandbox support (required for runkit_lint(), runkit_lint_file(), and
 *   the Runkit_Sandbox class) is only available as of PHP 5.1.0 or specially
 *   patched versions of PHP 5.0, and requires that thread safety be enabled.
 *   See the README file included in the runkit package for more information.
 * </blockquote>
 *
 * @param string $filename File containing PHP Code to be lint checked
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_lint_file($filename)
{
}

/**
 * Check the PHP syntax of the specified php code
 *
 * The runkit_lint() function performs a syntax (lint) check on the specified
 * php code testing for scripting errors. This is similar to using php -l from
 * the command line except runkit_lint() accepts actual code rather than a
 * filename.
 *
 * <blockquote>
 *   Note: Sandbox support (required for runkit_lint(), runkit_lint_file(), and
 *   the Runkit_Sandbox class) is only available as of PHP 5.1.0 or specially
 *   patched versions of PHP 5.0, and requires that thread safety be enabled.
 *   See the README file included in the runkit package for more information.
 * </blockquote>
 *
 * @param string $code PHP Code to be lint checked
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_lint($code)
{
}

/**
 * Dynamically adds a new method to a given class
 *
 * @param string  $classname  The class to which this method will be added
 * @param string  $methodname The name of the method to add
 * @param string  $args       Comma-delimited list of arguments for the
 *                            newly-created method
 * @param string  $code       The code to be evaluated when methodname is called
 * @param integer $flags      The type of method to create, can be
 *                            RUNKIT_ACC_PUBLIC, RUNKIT_ACC_PROTECTED or
 *                            RUNKIT_ACC_PRIVATE
 *                            <blockquote>
 *                              Note: This parameter is only used as of PHP 5,
 *                              because, prior to this, all methods were public.
 *                            </blockquote>
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_add($classname, $methodname, $args, $code, $flags = RUNKIT_ACC_PUBLIC)
{
}

/**
 * Copies a method from class to another
 *
 * @param string $dClass  Destination class for copied method
 * @param string $dMethod Destination method name
 * @param string $sClass  Source class of the method to copy
 * @param string $sMethod Name of the method to copy from the source class. If
 *                        this parameter is omitted, the value of dMethod is
 *                        assumed.
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_copy($dClass, $dMethod, $sClass, $sMethod = null)
{
}

/**
 * Dynamically changes the code of the given method
 *
 * <blockquote>
 *   Note: This function cannot be used to manipulate the currently running (or
 *   chained) method.
 * </blockquote>
 *
 * @param string  $classname  The class in which to redefine the method
 * @param string  $methodname The name of the method to redefine
 * @param string  $args       Comma-delimited list of arguments for the
 *                            redefined method
 * @param string  $code       The new code to be evaluated when methodname is
 *                            called
 * @param integer $flags      The redefined method can be RUNKIT_ACC_PUBLIC,
 *                            RUNKIT_ACC_PROTECTED or RUNKIT_ACC_PRIVATE
 *                            <blockquote>
 *                              Note: This parameter is only used as of PHP 5,
 *                              because, prior to this, all methods were public.
 *                            </blockquote>
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_redefine($classname, $methodname, $args, $code, $flags = RUNKIT_ACC_PUBLIC)
{
}

/**
 * Dynamically removes the given method
 *
 * <blockquote>
 *   Note: This function cannot be used to manipulate the currently running (or
 *   chained) method.
 * </blockquote>
 *
 * @param string $classname  The class in which to remove the method
 * @param string $methodname The name of the method to remove
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_remove($classname, $methodname)
{
}

/**
 * Dynamically changes the name of the given method
 *
 * <blockquote>
 *   Note: This function cannot be used to manipulate the currently running (or
 *   chained) method.
 * </blockquote>
 *
 * @param string $classname  The class in which to rename the method
 * @param string $methodname The name of the method to rename
 * @param string $newname    The new name to give to the renamed method
 *
 * @return boolean Returns TRUE on success or FALSE on failure.
 */
function runkit_method_rename($classname, $methodname, $newname)
{
}

/**
 * Determines if the current functions return value will be used
 *
 * <code>
 *  <?php
 *  function foo() {
 *  var_dump(runkit_return_value_used());
 *  }
 *
 *  foo();
 *  $f = foo();
 *  ?>
 * </code>
 *
 * The above example will output:
 *
 * <blockquote>
 *   bool(false)
 *   bool(true)
 * </blockquote>
 *
 * @return boolean Returns TRUE if the function's return value is used by the
 *                 calling scope, otherwise FALSE
 */
function runkit_return_value_used()
{
}

/**
 * Specify a function to capture and/or process output from a runkit sandbox
 *
 * Ordinarily, anything output (such as with echo or print) will be output as
 * though it were printed from the parent's scope. Using
 * runkit_sandbox_output_handler() however, output generated by the sandbox
 * (including errors), can be captured by a function outside of the sandbox.
 *
 * <blockquote>
 *   Note: Sandbox support (required for runkit_lint(), runkit_lint_file(), and
 *   the Runkit_Sandbox class) is only available as of PHP 5.1.0 or specially
 *   patched versions of PHP 5.0, and requires that thread safety be enabled.
 *   See the README file included in the runkit package for more information.
 * </blockquote>
 *
 * <blockquote>
 *   Note: Deprecated
 *
 *   As of runkit version 0.5, this function is deprecated and is scheduled to
 *   be removed from the package prior to a 1.0 release. The output handler for
 *   a given Runkit_Sandbox instance may be read/set using the array offset
 *   syntax shown on the Runkit_Sandbox class definition page.
 * </blockquote>
 *
 * <code>
 *  <?php
 *  function capture_output($str) {
 *  $GLOBALS['sandbox_output'] .= $str;
 *
 *  return '';
 *  }
 *
 *  $sandbox_output = '';
 *
 *  $php = new Runkit_Sandbox();
 *  runkit_sandbox_output_handler($php, 'capture_output');
 *  $php->echo("Hello\n");
 *  $php->eval('var_dump("Excuse me");');
 *  $php->die("I lost myself.");
 *  unset($php);
 *
 *  echo "Sandbox Complete\n\n";
 *  echo $sandbox_output;
 *  ?>
 * </code>
 *
 * The above example will output:
 *
 * <blockquote>
 *   Sandbox Complete
 *
 *   Hello
 *   string(9) "Excuse me"
 *   I lost myself.
 * </blockquote>
 *
 * @param object $sandbox  Object instance of Runkit_Sandbox class on which to
 *                         set output handling.
 * @param null   $callback Name of a function which expects one parameter.
 *                         Output generated by sandbox will be passed to this
 *                         callback. Anything returned by the callback will be
 *                         displayed normally. If this parameter is not passed
 *                         then output handling will not be changed. If a
 *                         non-truth value is passed, output handling will be
 *                         disabled and will revert to direct display.
 *
 * @return mixed Returns the name of the previously defined output handler
 *               callback, or FALSE if no handler was previously defined.
 */
function runkit_sandbox_output_handler($sandbox, $callback = null)
{
}

/**
 * Return numerically indexed array of registered superglobals
 *
 * @return array Returns a numerically indexed array of the currently registered
 *               superglobals. i.e. _GET, _POST, _REQUEST, _COOKIE, _SESSION,
 *               _SERVER, _ENV, _FILES
 */
function runkit_superglobals()
{
}