<?php

namespace es\ucm\fdi\aw\clases\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\clases\MagicProperties;

class Usuario
{

    use MagicProperties;

    public const ADMIN_ROLE = 1;
    public const MODERATOR_ROLE = 2;
    public const USER_ROLE = 3;

    public static function login($nombreUsuario, $password)
    {

        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->compruebaPassword($password)) {
            return self::cargaRoles($usuario);
        }
        return false;
    }

    public static function crea($nombreUsuario, $password, $rol, $capacidad_inventario, $dinero)
    {
        $user = new Usuario($nombreUsuario, self::hashPassword($password), null, $rol, $capacidad_inventario, $dinero);
        $user->añadeRol($rol);
        return $user->guarda();
    }

    public static function buscaUsuario($nombreUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios U WHERE U.nombre_usuario='%s'", $conn->real_escape_string($nombreUsuario));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['nombre_usuario'], $fila['password'], $fila['id'], $fila['rol'], $fila['capacidad_inventario'], $fila['dinero']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaNombreUsuario($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios U WHERE U.id='%s'", $conn->real_escape_string($idUsuario));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = $fila['nombre_usuario'];
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaPorId($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios WHERE id=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['nombre_usuario'], $fila['password'], $fila['id'], $fila['rol'], $fila['capacidad_inventario'], $fila['dinero']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaTodos()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM usuarios";
        $rs = $conn->query($query);
        $usuarios = array();
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $usuario = new Usuario($fila['nombre_usuario'], $fila['password'], $fila['id'], $fila['rol'], $fila['capacidad_inventario'], $fila['dinero']);
                array_push($usuarios, $usuario);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $usuarios;
    }
    public static function mostrarTablaUsuarios()
    {
        $usuarios = Usuario::buscaTodos();
        echo "<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>";
        foreach ($usuarios as $usuario) {
            echo "<tr>
                <td>{$usuario->getId()}</td>
                <td>{$usuario->getNombreUsuario()}</td>
                <td>{$usuario->getRoles()}</td>
                <td>
                    <form method='POST' action='ruta/del/archivo'>
                        <input type='hidden' name='id' value='{$usuario->getId()}'>
                        <input type='submit' value='Modificar'>
                    </form>
                    <form method='POST' action='./BorrarUsuario.php'>
                        <input type='hidden' name='id' value='{$usuario->getId()}'>
                        <input type='submit' value='Eliminar'>
                    </form>
                </td>
            </tr>";
        }

        echo "</tbody>
    </table>";
        return null;
    }

    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private static function cargaRoles($usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT rol FROM usuarios WHERE id=%d", $usuario->id);
        $rs = $conn->query($query);
        if ($rs) {
            $rol = $rs->fetch_all(MYSQLI_ASSOC);
            $rs->free();

            foreach ($rol as $rol) {
                $usuario->rol = $rol['rol'];
            }
            return $usuario;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return false;
    }
    private static function inserta($usuario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "INSERT INTO Usuarios(nombre_usuario, password,capacidad_inventario,dinero) VALUES ('%s', '%s', 40,500)",
            $conn->real_escape_string($usuario->nombreUsuario),
            $conn->real_escape_string($usuario->password)
        );
        if ($conn->query($query)) {
            $usuario->id = $conn->insert_id;
            $result = self::insertaRoles($usuario);
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function insertaRoles($usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();

        $rol = $usuario->rol;
        $query = sprintf("UPDATE usuarios SET rol = %d WHERE id = %d", $rol, $usuario->id);
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $usuario;
    }

    private static function actualiza($usuario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "UPDATE usuarios U SET nombre_usuario = '%s', password='%s' WHERE U.id=%d",
            $conn->real_escape_string($usuario->nombreUsuario),
            $conn->real_escape_string($usuario->password),
            $usuario->id
        );
        if ($conn->query($query)) {
            $result = self::borraRoles($usuario);
            if ($result) {
                $result = self::insertaRoles($usuario);
            }
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $result;
    }

    private static function borraRoles($usuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "DELETE FROM usuarios WHERE id = %d",
            $usuario->id
        );
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $usuario;
    }

    private static function borra($usuario)
    {
        return self::borraPorId($usuario->id);
    }

    public static function borraPorId($idUsuario)
    {
        if (!$idUsuario) {
            return false;
        }
        /* Los roles se borran en cascada por la FK
         * $result = self::borraRoles($usuario) !== false;
         */
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf(
            "DELETE FROM usuarios WHERE id = %d",
            $idUsuario
        );
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    public static function restaDinero($monto, $idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $carteraSql = sprintf("SELECT dinero FROM usuarios WHERE id = %d", $idUsuario);
        $cartera = $conn->query($carteraSql)->fetch_assoc()['dinero'];

        if ($cartera - $monto >= 0) {
            $cartera -= $monto;
            $actualiza = sprintf("UPDATE usuarios SET dinero = %d WHERE id=%d", $cartera, $idUsuario);
            if (!$conn->query($actualiza)) {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        } else {
            return false;
        }
    }


    public static function sumaDinero($monto, $idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $cartera = sprintf("SELECT dinero FROM usuarios WHERE id = %d", $idUsuario);
        $result = $conn->query($cartera);
        if (!$result) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        $dinero = $result->fetch_assoc()['dinero'];
        $actualiza = sprintf("UPDATE usuarios SET dinero = '%d' WHERE id=%d", $dinero + $monto, $idUsuario);
        if (!$conn->query($actualiza)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }


    private $id;

    private $nombreUsuario;

    private $password;

    private $rol;

    private $capacidad_inventario;

    private $dinero;
    private function __construct($nombreUsuario, $password, $id, $rol, $capacidad_inventario, $dinero)
    {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->rol = $rol;
        $this->capacidad_inventario = $capacidad_inventario;
        $this->dinero = $dinero;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function añadeRol($role)
    {
        $this->rol = $role;
    }

    public function getRoles()
    {
        return $this->rol;
    }

    public function getCapacidad_inventario()
    {
        return $this->capacidad_inventario;
    }

    public function getDinero()
    {
        return $this->dinero;
    }

    public function tieneRol($role)
    {
        if ($this->rol == null) {
            self::cargaRoles($this);
        }
        return array_search($role, $this->rol) !== false;
    }

    public function compruebaPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function cambiaPassword($nuevoPassword)
    {
        $this->password = self::hashPassword($nuevoPassword);
    }

    public function guarda()
    {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }

    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }
}
