<?php
namespace env\globals;
/* variable $_FILES 
 *
 *
 * @example
 *		env()->files = new \env\globals\files;
 *		$post_name = 'example';				// <input> name in the form
 *		env()->files[$post_name]->name ;	// uploaded file name
 *		env()->files[$post_name]->tmp_name;	// tmp path
 *		env()->files[$post_name]->size;		// file size
 *		env()->files[$post_name]->error;	// upload error
 *
 *		env()->files[$post_name]->move_to("/path/of/somewhere.format");	// savely moving uploaded file
 *
 * */

class files implements \env\iglobals {
}
