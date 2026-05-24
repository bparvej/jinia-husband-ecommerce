function handleDbConnectionError(error, logger) {
  const dbName = process.env.DB_NAME || 'homei_db';
  const dbHost = process.env.DB_HOST || 'localhost';
  const dbPort = process.env.DB_PORT || 3306;

  console.log('\n=============================================================');
  console.log('                      🛑 DATABASE ERROR 🛑                     ');
  console.log('=============================================================\n');

  if (error.name === 'SequelizeConnectionRefusedError') {
    logger.error(`❌ Connection Refused: Could not connect to MySQL at ${dbHost}:${dbPort}.`);
    console.log(`\n💡 FIX: Please ensure your MySQL server is running locally and listening on port ${dbPort}.`);
  } else if (error.name === 'SequelizeAccessDeniedError') {
    logger.error(`❌ Access Denied: Incorrect MySQL username or password.`);
    console.log(`\n💡 FIX: Please check the DB_USER and DB_PASSWORD variables in your .env file.`);
  } else if (error.name === 'SequelizeHostNotFoundError' || error.name === 'SequelizeHostNotReachableError') {
    logger.error(`❌ Host Not Found: Could not resolve the MySQL host "${dbHost}".`);
    console.log(`\n💡 FIX: Please verify the DB_HOST variable in your .env file.`);
  } else if (error.message && error.message.includes('Unknown database')) {
    logger.error(`❌ Database Not Found: The database "${dbName}" does not exist.`);
    console.log(`\n💡 FIX: Please create the database by running the following SQL command in your MySQL console:`);
    console.log(`        CREATE DATABASE ${dbName};`);
  } else {
    // Generic fallback
    logger.error(`❌ Database Connection Failed: ${error.message}`);
    console.log(`\n💡 FIX: Please check your database configuration in the .env file and ensure MySQL is running.`);
  }

  console.log('\n=============================================================\n');
}

module.exports = { handleDbConnectionError };
