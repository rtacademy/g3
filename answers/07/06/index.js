// $ npm i -g csv-parser
// $ node index.js > dump.sql

// $ docker cp dump.sql rtacademy_database_postgresql:/tmp/
// $ docker exec -it rtacademy_database_postgresql /bin/bash -c "psql -U helloworld -d helloworld < /tmp/dump.sql"
// $> pg_dump -U helloworld -d helloworld --file=/tmp/postgresql.dump.sql

// $ docker cp dump.sql rtacademy_database_mariadb:/tmp/
// $ docker exec -it rtacademy_database_mariadb /bin/bash -c "mysql -u helloworld -p helloworld < /tmp/dump.sql"
// $> mysqldump -u helloworld -p helloworld --complete-insert > /tmp/mariadb.dump.sql

const fs  = require( 'fs' );
const csv = require( '/usr/lib/node_modules/csv-parser' );

const escapeQuotes = ( str ) => str.replace( '\\', '' ).replace( '`', '\'' ).replace( '"', '\'' ).replace( /'/g, "''" );

(
    () =>
    {
        const results = [];

        fs.createReadStream( __dirname + '/GeoLite2-City-Locations-en.csv' )
            .pipe( csv() )
            .on( 'data', ( data ) => results.push( data ) )
            .on(
                'end', () =>
                {
                    const continents = {};
                    const countries = {};
                    const cities = [];

                    let continent_id = 1;
                    let country_id = 1;
                    let city_id = 1;

                    results
                        .filter( i =>
                            i.continent_name.length > 2 && i.country_name.length > 2 &&
                            i.city_name.length > 2 && i.city_name.indexOf( 'Oblast' ) === -1 )
                        .forEach(
                            i =>
                            {
                                if( !continents.hasOwnProperty( i.continent_name ) )
                                {
                                    continents[ i.continent_name ] =
                                    {
                                        'id'  : continent_id,
                                        'code': ( i.continent_code || '' ).toUpperCase(),
                                        'name': i.continent_name
                                    };

                                    ++continent_id;
                                }

                                if( !countries.hasOwnProperty( i.country_name ) )
                                {
                                    countries[ i.country_name ] =
                                    {
                                        'id'  : country_id,
                                        'code': ( i.country_iso_code || '' ).toUpperCase(),
                                        'name': i.country_name
                                    };

                                    ++country_id;
                                }

                                cities.push(
                                    {
                                        'id'          : city_id,
                                        'continent_id': continents[ i.continent_name ][ 'id' ],
                                        'country_id'  : countries[ i.country_name ][ 'id' ],
                                        'name'        : i.city_name,
                                        'time_zone'   : i.time_zone || 'UTC+0',
                                    }
                                );

                                ++city_id;
                            }
                    );

                    let sqlTruncate =
                        'SET FOREIGN_KEY_CHECKS = 0;\n' +       // йо___й мускуль
                        'truncate table cities;\n' +
                        'truncate table continents;\n' +
                        'truncate table countries;\n' +
                        'SET FOREIGN_KEY_CHECKS = 1;\n';        // йо___й мускуль

                    process.stdout.write( sqlTruncate + "\n" );

                    let sqlContinents = '';

                    Object.values( continents ).forEach(
                        i =>
                        {
                            sqlContinents += `insert into continents (id, code, name) values (${ i.id }, '${ i.code }', '${ escapeQuotes(i.name) }');\n`;
                        }
                    );

                    process.stdout.write( sqlContinents + "\n" );

                    let sqlCountries = '';

                    Object.values( countries ).forEach(
                        i =>
                        {
                            sqlCountries += `insert into countries (id, code, name) values (${ i.id }, '${ i.code }', '${ escapeQuotes(i.name) }');\n`;
                        }
                    );

                    process.stdout.write( sqlCountries + "\n" );

                    let sqlCities = '';

                    Object.values( cities ).forEach(
                        i =>
                        {
                            sqlCities += `insert into cities (id, continent_id, country_id, name, time_zone) values (${ i.id }, ${ i.continent_id }, ${ i.country_id }, '${ escapeQuotes(i.name) }',  '${ i.time_zone }');\n`;
                        }
                    );

                    process.stdout.write( sqlCities + "\n" );
                }
            );
    }
)();