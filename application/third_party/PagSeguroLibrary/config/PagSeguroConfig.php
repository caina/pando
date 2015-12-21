<?php
/**
 * 2007-2014 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    PagSeguro Internet Ltda.
 *  @copyright 2007-2014 PagSeguro Internet Ltda.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */

$PagSeguroConfig = array();

$PagSeguroConfig['environment'] = "production"; // production, sandbox

$PagSeguroConfig['credentials'] = array();
$PagSeguroConfig['credentials']['email'] = "caina27@gmail.com";
$PagSeguroConfig['credentials']['token']['production'] = "1F437AAECC2249C59E1DCFF8166D18F1";
$PagSeguroConfig['credentials']['token']['sandbox'] = "C11D6BB7D2554BFF9CE76FC21A551820";
$PagSeguroConfig['credentials']['appId']['production'] = "your__production_pagseguro_application_id";
$PagSeguroConfig['credentials']['appId']['sandbox'] = "app4558572495";
$PagSeguroConfig['credentials']['appKey']['production'] = "your_production_application_key";
$PagSeguroConfig['credentials']['appKey']['sandbox'] = "539B8E73A7A77019949DDF96C155F8F5";

$PagSeguroConfig['application'] = array();
$PagSeguroConfig['application']['charset'] = "UTF-8"; // UTF-8, ISO-8859-1

$PagSeguroConfig['log'] = array();
$PagSeguroConfig['log']['active'] = false;
// Informe o path completo (relativo ao path da lib) para o arquivo, ex.: ../PagSeguroLibrary/logs.txt
$PagSeguroConfig['log']['fileLocation'] = "";
