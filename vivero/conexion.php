<?php
// Nombre de la base de datos en archivo local
$db_file = __DIR__ . '/vivero.db';
$es_nueva_bd = !file_exists($db_file);

try {
    // Conexión mediante PDO a SQLite
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar con SQLite: " . $e->getMessage());
}

// Adaptador para que tus scripts mantengan compatibilidad con la sintaxis MySQLi
class SQLiteAdapter
{
    private $pdo;
    public $insert_id = 0;
    public $error = "";

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function query($sql)
    {
        $this->error = "";
        // Convertir funciones específicas de MySQL a sintaxis SQLite
        $sql_clean = str_ireplace('CURDATE()', "date('now')", $sql);

        try {
            $query_type = strtoupper(trim($sql_clean));
            if (strpos($query_type, 'SELECT') === 0) {
                $stmt = $this->pdo->query($sql_clean);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return new SQLiteResultAdapter($rows);
            } else {
                $this->pdo->exec($sql_clean);
                $this->insert_id = $this->pdo->lastInsertId();
                return true;
            }
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function real_escape_string($string)
    {
        return str_replace("'", "''", $string);
    }
}

class SQLiteResultAdapter
{
    private $rows;
    private $index = 0;
    public $num_rows = 0;

    public function __construct($rows)
    {
        $this->rows = $rows ? $rows : [];
        $this->num_rows = count($this->rows);
    }

    public function fetch_assoc()
    {
        if ($this->index < $this->num_rows) {
            return $this->rows[$this->index++];
        }
        return null;
    }
}

// Instancia global de la conexión
$conn = new SQLiteAdapter($pdo);

// Si el archivo vivero.db no existía, creamos la estructura automáticamente
if ($es_nueva_bd) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id_usuario INTEGER PRIMARY KEY AUTOINCREMENT,
            usuario TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            rol TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS inventarios (
            id_inventario INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS productos (
            id_producto INTEGER PRIMARY KEY AUTOINCREMENT,
            id_inventario INTEGER NOT NULL,
            sku TEXT,
            nombre TEXT NOT NULL,
            precio_costo REAL NOT NULL,
            precio_venta REAL NOT NULL,
            stock_actual INTEGER NOT NULL,
            punto_reorden INTEGER NOT NULL,
            fecha_ultima_recepcion TEXT,
            cantidad_optima_pedido INTEGER NOT NULL,
            FOREIGN KEY (id_inventario) REFERENCES inventarios(id_inventario)
        );

        -- Usuarios predeterminados
        INSERT INTO usuarios (usuario, password, rol) VALUES ('jefe', '1234', 'gerente');
        INSERT INTO usuarios (usuario, password, rol) VALUES ('empleado1', '1234', 'empleado');

        -- Categorías iniciales
        INSERT INTO inventarios (id_inventario, nombre) VALUES (1, 'Plantas');
        INSERT INTO inventarios (id_inventario, nombre) VALUES (2, 'Plásticos');
        INSERT INTO inventarios (id_inventario, nombre) VALUES (3, 'Herramientas');
    ");
}
