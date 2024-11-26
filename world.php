<?php

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123'; 
$dbname = 'world';

try {
    // Establish database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the 'country' parameter is set
    if (isset($_GET['country'])) {
        $country = $_GET['country'];
        $lookupType = $_GET['lookup'] ?? 'country'; // Default to country lookup if not specified

        // Handle country lookup
        if ($lookupType === 'country') {
            $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country");
            $stmt->execute(['country' => "%$country%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Generate HTML for country results
            if ($results) {
                echo "<table>";
                echo "<thead>
                        <tr>
                            <th>Country</th>
                            <th>Continent</th>
                            <th>Independence Year</th>
                            <th>Head of State</th>
                        </tr>
                      </thead>";
                echo "<tbody>";
                foreach ($results as $row) {
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['continent']}</td>
                            <td>" . ($row['independence_year'] ?? 'N/A') . "</td>
                            <td>{$row['head_of_state']}</td>
                          </tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "No results found for countries.";
            }
        }

        // Handle city lookup
        elseif ($lookupType === 'cities') {
            $stmt = $conn->prepare("SELECT cities.name AS city, cities.district, cities.population 
                                    FROM cities 
                                    JOIN countries ON cities.country_code = countries.code 
                                    WHERE countries.name LIKE :country");
            $stmt->execute(['country' => "%$country%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Generate HTML for city results
            if ($results) {
                echo "<table>";
                echo "<thead>
                        <tr>
                            <th>City</th>
                            <th>District</th>
                            <th>Population</th>
                        </tr>
                      </thead>";
                echo "<tbody>";
                foreach ($results as $row) {
                    echo "<tr>
                            <td>{$row['city']}</td>
                            <td>{$row['district']}</td>
                            <td>{$row['population']}</td>
                          </tr>";
                }
                echo "</tbody>";
                echo "</table>";
            } else {
                echo "No results found for cities.";
            }
        }
    } else {
        echo "Please specify a country.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
