const fs = require('fs');
const path = require('path');
const { Client } = require('pg');
require('dotenv').config({ path: path.join(__dirname, '../backend/.env') });

// Configuration
const pgConfig = {
  user: process.env.PGUSER || 'postgres',
  password: process.env.PGPASSWORD !== undefined ? process.env.PGPASSWORD : '',
  host: process.env.PGHOST || 'localhost',
  port: parseInt(process.env.PGPORT || '5432'),
  database: process.env.PGDATABASE || 'anime_site'
};

// Dependency order for PostgreSQL insertion
const tableOrder = ['genres', 'type', 'users', 'anime', 'episodes', 'watchlist', 'history'];

async function migrate() {
  console.log('Connecting to PostgreSQL to check database existence...');

  // Connect to default 'postgres' database to create the target database if not exists
  const tempConfig = { ...pgConfig, database: 'postgres' };
  const initClient = new Client(tempConfig);

  try {
    await initClient.connect();
    
    // Check if database exists
    const dbCheck = await initClient.query('SELECT 1 FROM pg_database WHERE datname = $1', [pgConfig.database]);
    if (dbCheck.rows.length === 0) {
      console.log(`Database "${pgConfig.database}" does not exist. Creating...`);
      await initClient.query(`CREATE DATABASE "${pgConfig.database}"`);
      console.log(`Database "${pgConfig.database}" created successfully.`);
    } else {
      console.log(`Database "${pgConfig.database}" already exists.`);
    }
  } catch (err) {
    console.error('Error checking/creating database:', err.message);
    console.log('Please make sure PostgreSQL is running and credentials in backend/.env are correct.');
    return;
  } finally {
    try {
      await initClient.end();
    } catch (e) {}
  }

  // Connect to target database
  console.log(`Connecting to target database "${pgConfig.database}"...`);
  const client = new Client(pgConfig);

  try {
    await client.connect();
    console.log('Connected to target database.');

    // 1. Initialize schema
    console.log('Initializing schema from init.sql...');
    const schemaSql = fs.readFileSync(path.join(__dirname, 'init.sql'), 'utf8');
    await client.query(schemaSql);
    console.log('Schema initialized successfully.');

    // 2. Parse and load data from anime_site.sql
    console.log('Reading and parsing data from anime_site.sql...');
    const rawSql = fs.readFileSync(path.join(__dirname, 'anime_site.sql'), 'utf8');
    const lines = rawSql.split(/\r?\n/);
    
    // Truncate tables in reverse dependency order
    console.log('Cleaning existing tables...');
    for (const table of [...tableOrder].reverse()) {
      await client.query(`TRUNCATE TABLE "${table}" CASCADE`);
    }

    // Collect all insert statements
    const insertsByTable = {};
    tableOrder.forEach(table => {
      insertsByTable[table] = [];
    });
    
    let currentInsert = '';
    
    for (let i = 0; i < lines.length; i++) {
      let line = lines[i].trim();
      
      // Skip comments and empty lines
      if (!line || line.startsWith('--') || line.startsWith('/*')) {
        continue;
      }
      
      if (line.toUpperCase().startsWith('INSERT INTO')) {
        currentInsert = line;
      } else if (currentInsert) {
        currentInsert += ' ' + line;
      }
      
      // Check if this is the end of an insert statement
      if (currentInsert && currentInsert.endsWith(';')) {
        // Clean insert statement for PostgreSQL compatibility
        let pgInsert = currentInsert.replace(/`([^`]+)`/g, '"$1"');
        pgInsert = pgInsert.replace(/\\'/g, "''");
        pgInsert = pgInsert.replace(/\\"/g, '"');
        pgInsert = pgInsert.replace(/INSERT IGNORE INTO/gi, 'INSERT INTO');
        
        // Find which table it belongs to
        const match = pgInsert.match(/INSERT INTO\s+"?(\w+)"?/i);
        if (match) {
          const tableName = match[1].toLowerCase();
          if (insertsByTable[tableName]) {
            insertsByTable[tableName].push(pgInsert);
          } else {
            console.log(`Warning: Found insert for unknown/untracked table "${tableName}"`);
          }
        }
        
        currentInsert = '';
      }
    }
    
    // Run the insertions in dependency order
    console.log('Migrating data in dependency order...');
    let totalExecuted = 0;
    
    for (const table of tableOrder) {
      const inserts = insertsByTable[table];
      if (inserts.length > 0) {
        console.log(`Inserting ${inserts.length} statement(s) into table "${table}"...`);
        for (const insertSql of inserts) {
          try {
            await client.query(insertSql);
            totalExecuted++;
          } catch (err) {
            console.error(`Error executing insert into "${table}":`, err.message);
          }
        }
      }
    }
    
    console.log(`Successfully executed ${totalExecuted} insert statements.`);

    // 3. Reset table ID sequences
    console.log('Updating PostgreSQL sequence values...');
    const sequences = [
      { table: 'users', id: 'user_id' },
      { table: 'anime', id: 'anime_id' },
      { table: 'episodes', id: 'episode_id' },
      { table: 'genres', id: 'genre_id' },
      { table: 'type', id: 'id' },
      { table: 'history', id: 'id' },
      { table: 'watchlist', id: 'id' }
    ];

    for (const seq of sequences) {
      try {
        const query = `
          SELECT setval(
            pg_get_serial_sequence('${seq.table}', '${seq.id}'), 
            coalesce(max(${seq.id}), 1)
          ) FROM "${seq.table}"
        `;
        await client.query(query);
      } catch (err) {
        console.error(`Could not reset sequence for ${seq.table}.${seq.id}:`, err.message);
      }
    }
    console.log('Database sequences updated.');
    console.log('Migration completed successfully!');

  } catch (err) {
    console.error('Migration failed:', err.message);
  } finally {
    await client.end();
  }
}

migrate();
