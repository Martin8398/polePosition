<?php
function sessionAuthMiddleware($res)
{
    if (isset($_SESSION['usuario_id'])) {
        $res->user = new stdClass;
        // $res->user->id = $_SESSION['usuario_id'];
        $res->user->usuario = $_SESSION['usuario_id'];
        return;
    }
}
