<?php
namespace househub\users\tables;

class UserViewPermissionsTable{
	
	const TABLE_NAME = 'users_view_permissions';
	
	const COLUMN_ID = 'id';
	const COLUMN_USER_ID = 'user_id';
	const COLUMN_OBJECT_ID = 'object_id';
	const COLUMN_GRANT_DATE = 'grant_date';
}

?>