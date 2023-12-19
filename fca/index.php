<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/_fca/core/core.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/_fca/ext/ext.php");

const FCA = true;
FImport::php("_fca/user/user.config");
FImport::php("_fca/user/user.field");
FImport::api();