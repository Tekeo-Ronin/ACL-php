<?php
interface iVerifier {
    public function check($userId, $serviceName);
    public function report($userId, $serviceName);
}
