<?php
/**
 * README
 * This configuration file is intended to run the bot with the webhook method.
 * Uncommented parameters must be filled
 *
 * Please note that if you open this file with your browser you'll get the "Input is empty!" Exception.
 * This is a normal behaviour because this address has to be reached only by the Telegram servers.
 */

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
 
// Load composer
require_once __DIR__ . '/vendor/autoload.php';

// Add you bot's API key and name
$bot_api_key  = '758626006:AAGgKnowYgvrBx5F_cQPkavMCr6iqE1w-wY';
$bot_username = 'foodstar_bot';

// Define all IDs of admin users in this array (leave as empty array if not used)
$admin_users = [
];

$config = [
	'admin_group' => -123456789,
	'base_url' => 'http://torycast.herokuapp.com/',
	'download_path' => 'Download/'
];

// Define all paths for your custom commands in this array (leave as empty array if not used)
$commands_paths = [
    __DIR__ . '/Commands/',
];

// Enter your MySQL database credentials
$mysql_credentials = [
    'host'     => 'localhost',
    'user'     => 'example_user',
	'password' => 'example_password',
    'database' => 'example_database',
];

$botan_key = 'example-key';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Add commands paths containing your custom commands
    $telegram->addCommandsPaths($commands_paths);

    // Enable admin users
    $telegram->enableAdmins($admin_users);

    // Enable MySQL
    $telegram->enableMySql($mysql_credentials);

    // Logging (Error, Debug and Raw Updates)
    Longman\TelegramBot\TelegramLog::initErrorLog(__DIR__ . "/{$bot_username}_error.log");
    //Longman\TelegramBot\TelegramLog::initDebugLog(__DIR__ . "/{$bot_username}_debug.log");
    //Longman\TelegramBot\TelegramLog::initUpdateLog(__DIR__ . "/{$bot_username}_update.log");

    // If you are using a custom Monolog instance for logging, use this instead of the above
    //Longman\TelegramBot\TelegramLog::initialize($your_external_monolog_instance);

    // Set custom Upload and Download paths
    $telegram->setDownloadPath(__DIR__ . '/Download');
    $telegram->setUploadPath(__DIR__ . '/Upload');

    // Here you can set some command specific parameters
    // e.g. Google geocode/timezone api key for /date command
    $telegram->setCommandConfig('dummyCommand', $config);

    // Botan.io integration
    $telegram->enableBotan($botan_key);

    // Requests Limiter (tries to prevent reaching Telegram API limits)
    $telegram->enableLimiter();
	
    // Handle telegram webhook request
    $telegram->handle();

} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    //echo $e;
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Silence is golden!
    // Uncomment this to catch log initialisation errors
    //echo $e;
}
