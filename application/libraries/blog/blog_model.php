<?php
class Blog_model extends Dynamic_model{
	var $table_prefix = "";
	var $max_lines_show = 10000;

	public function __construct(){
		parent::__construct(); 
     }

	public function createPost(){

	}

	function save_facebook_data($data){
		$this->get_db()->truncate('blog_facebook_data');
		$this->get_db()->insert('blog_facebook_data', $data);
	}

	function get_facebook_data(){
		if(!$this->get_db()->table_exists('blog_facebook_data')){
			$table = "
				CREATE TABLE blog_facebook_data (
	            `id` int(11) NOT NULL AUTO_INCREMENT,
	            `page_code` varchar(255)  NULL,
	            `website_blog_page` varchar(255)  NULL,
	            PRIMARY KEY (`id`)
	          );
			";
			$this->get_db()->query($table);
		}
		return $this->get_db()->get('blog_facebook_data')->row();

	}

	function get_post($post_id,$language=false){
		$condition = array("id"=>$post_id);
		if($language){
			$condition = array("id_blog_post"=>$post_id, "language_flag"=>$language);
		}
		return $this->get_db()->get_where("{$this->table_prefix}blog_post",$condition)->row();
	}

	function get_categories_by_post($post_id){
		$query = "
			select * from {$this->table_prefix}blog_category cat
			join {$this->table_prefix}blog_post_category  post on post.id_blog_category = cat.id
		";
		return $this->get_db()->query($query)->result();
	}

	function update_languages_post($blog_post_id,$post_data){
		if(isset($post_data["action"])){
			$data = array("action"=>$post_data["action"]);
			$this->get_db()->update("{$this->table_prefix}blog_post", $data, array("id_blog_post"=>$blog_post_id));
			$this->get_db()->update("{$this->table_prefix}blog_post", $data, array("id"=>$blog_post_id));
			// dump($post_data);
		}
		// $data = array()
	}

	function delete_post($id){
		$database_blog_post = $this->get_db()->select("id")->where("id_blog_post")->get("{$this->table_prefix}blog_post")->result();
		foreach ($database_blog_post as $post) {
			$this->get_db()->delete("{$this->table_prefix}blog_comments", array("id_blog_post"=>$post->id));
			$this->get_db()->delete("{$this->table_prefix}blog_post_view", array("id_blog_post"=>$post->id));
			$this->get_db()->delete("{$this->table_prefix}blog_post_category", array("id_blog_post"=>$post->id));
			$this->get_db()->delete("{$this->table_prefix}blog_post", array("id"=>$post->id));
			$this->get_db()->delete("{$this->table_prefix}blog_post", array("id_blog_post"=>$post->id));
		}
		
		$this->get_db()->delete("{$this->table_prefix}blog_comments", array("id_blog_post"=>$id));
		$this->get_db()->delete("{$this->table_prefix}blog_post_view", array("id_blog_post"=>$id));
		$this->get_db()->delete("{$this->table_prefix}blog_post_category", array("id_blog_post"=>$id));
		$this->get_db()->delete("{$this->table_prefix}blog_post", array("id"=>$id));
		$this->get_db()->delete("{$this->table_prefix}blog_post", array("id_blog_post"=>$id));
	}

	function list_posts($current, $search=false){
		$where = "where 1=1";
		if($search){
			$search = str_replace(" ", "%", $search);
			$where = "where p.title like '%{$search}%'";
		}
		$query = "
			SELECT
				p.*,
				(
					SELECT
						COUNT(b.id)
					FROM
						{$this->table_prefix}blog_comments AS b
					WHERE
						b.id_blog_post IN (
							SELECT
								cp.id
							FROM
								{$this->table_prefix}blog_post cp
							WHERE
								cp.id = p.id
							OR cp.id_blog_post = p.id
						)

				)AS comments,
				(
					SELECT
						count(c.id_blog_post)
					FROM
						{$this->table_prefix}blog_post_view c
					WHERE
						c.id_blog_post IN(
							SELECT
								cp.id
							FROM
								{$this->table_prefix}blog_post cp
							WHERE
								cp.id = p.id
							OR cp.id_blog_post = p.id
						)
				)AS post_view
				
			FROM
				{$this->table_prefix}blog_post AS p
			{$where}
			and id_blog_post = 0
			order by p.post_date DESC
			limit {$current}, {$this->max_lines_show}
		";
		$a =  $this->get_db()->query($query)->result();
		// dump($this->get_db()->last_query());
		return $a;
	}

	function add_category($category){
		
		$database_category = $this->get_db()->get_where("{$this->table_prefix}blog_category",array("title"=>$category));
		if($database_category->num_rows == 0){
			$this->get_db()->insert("{$this->table_prefix}blog_category", array("title"=>$category));
		}
	}

	function remove_category($category){
		$this->get_db()->delete("{$this->table_prefix}blog_category", array("id"=>$category));
	}

	function get_categories($post=false){
		if($post){
			$query = "
				SELECT
				cat.*,
				(
					SELECT
						teg.id_blog_post
					FROM
					{$this->table_prefix}blog_post_category teg
					WHERE
						teg.id_blog_category = cat.id
					AND teg.id_blog_post = {$post}
				)AS blog_check
			FROM
				{$this->table_prefix}blog_category cat

			";

			return $this->get_db()->query($query)->result();
		}else{

			return $this->get_db()->get("{$this->table_prefix}blog_category")->result();
		}
	}

	function insert_post($post){
		// dump($post);
		$database_data = array();
		$database_meta = $this->blog_model->get_field_data("{$this->table_prefix}blog_post");
		
		foreach ($database_meta as $meta) {
			if(isset($post[$meta->name])){
				$database_data[$meta->name] = $post[$meta->name]; 
			}
		}

		if(!empty($database_data["id"])){
			$post_id = $database_data["id"];
			unset($database_data["id"]);
			$this->get_db()->update("{$this->table_prefix}blog_post", $database_data, array("id"=>$post_id));
			return $post_id;
		}else{
			$this->get_db()->insert("{$this->table_prefix}blog_post", $database_data);
			return $this->get_db()->insert_id();
		}
	}

	function get_new_comments(){
		$query = "
		select 
			com.*,
			 (select title from {$this->table_prefix}blog_post p where p.id = com.id_blog_post) as post
		from 
			{$this->table_prefix}blog_comments com
		where com.aproved = 0
		order by com.id_blog_post desc
		";

		$database_comments = $this->get_db()->query($query);
		$return_arr["total_comments"] 	= $database_comments->num_rows;
		$return_arr["comments"]			= $database_comments->result();
		return $return_arr;
	}

	function comment_action($id, $action){
		if($action=="aprove"){
			$data = array("aproved"=>1);
			$condition = array("id"=>$id);
			$this->get_db()->update("{$this->table_prefix}blog_comments", $data, $condition);
			return array("response"=>"Comentário Aprovado");
		}else{
			$array = array("id"=>$id);
			$this->get_db()->delete("{$this->table_prefix}blog_comments", $array);
			return array("response"=>"Comentário Rejeitado");
		}
	}


	function updade_gallery_images($post_id,$images){
		$this->db->delete('blog_gallery', array("blog_post_id"=>$post_id));
		if(!empty($images)){
			foreach ($images as $image) {
				$this->get_db()->insert('blog_gallery', array("image"=>$image,"blog_post_id"=>$post_id));
			}
		}
	}

	function get_gallery_images($post_id){
		return $this->get_db()->get_where('blog_gallery', array("blog_post_id"=>$post_id))->result();	
	}

	function can_display_multiupload(){
		return $this->get_db()->table_exists('blog_gallery');
	}

	function get_totalizadores(){
		$query = "
			SELECT
				(SELECT count(id) FROM {$this->table_prefix}blog_post b where b.id_blog_post = 0)AS postagens,
				(
					SELECT
						count(id)
					FROM
						{$this->table_prefix}blog_comments
					where aproved = 1
				)AS comentarios,
				(
					SELECT
						count(id)
					FROM
						{$this->table_prefix}blog_comments
					where aproved = 0
				)AS comentarios_novos,
				
				(
					SELECT
						count(id_blog_post)
					FROM
						{$this->table_prefix}blog_post_view
				)AS visualizacoes
			FROM
				DUAL
			";
		return current($this->get_db()->query($query)->result());
	}


	function inser_blog_categories($post_id, $categories){
		$this->get_db()->where('id_blog_post', $post_id)->delete("{$this->table_prefix}blog_post_category");

		foreach ($categories as $cat) {
			if(!empty($cat)){
				$this->get_db()->insert("{$this->table_prefix}blog_post_category", array("id_blog_post"=>$post_id, "id_blog_category"=>$cat));
			}
		}
	}


	public function runBasicSql(){

		$basicSQL = <<<EOT
		create table if not exists {$this->table_prefix}blog_post(
			id int not null PRIMARY KEY auto_increment,
			id_user int null,
			id_brand int null,
			title varchar(90) null,
			slug varchar(90) null,
			cover_photo varchar(255) null,
			entry text null,
			tags varchar(255) null,
			post_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			action int not null DEFAULT 0,
			id_blog_post int not null DEFAULT 0,
			language_flag varchar(40) not null DEFAULT 'pt'
		);

		create table if not exists {$this->table_prefix}blog_category(
			id int not null PRIMARY KEY auto_increment,
			title varchar(90) null
		);

		create table if not exists {$this->table_prefix}blog_post_category(
			id int not null PRIMARY KEY auto_increment,
			id_blog_post int not null,
			id_blog_category int not null
		);

		ALTER TABLE {$this->table_prefix}blog_post_category ADD INDEX `id_blog_post` (id_blog_post);
		ALTER TABLE {$this->table_prefix}blog_post_category ADD INDEX `id_blog_category` (id_blog_category);

		create table if not exists  {$this->table_prefix}blog_post_view(
			id_blog_post int not null
		);

		ALTER TABLE {$this->table_prefix}blog_post_view ADD INDEX `index` (id_blog_post);

		create table if not exists {$this->table_prefix}blog_comments(
			id int not null PRIMARY KEY auto_increment,
			id_blog_post int not null,
			`name` varchar(90) not null, 
			email varchar(90) not null,
			`comment` text not null,
			aproved TINYINT default 0,
			commnet_date timestamp DEFAULT CURRENT_TIMESTAMP
		);

		CREATE TABLE if not exists `blog_gallery` (
		  `id` int(255) NOT NULL AUTO_INCREMENT,
		  `image` varchar(255) NOT NULL,
		  `blog_post_id` int(11) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `blog_post_galery` (`blog_post_id`)
		);
	
		ALTER TABLE {$this->table_prefix}blog_comments ADD INDEX `id_blog_post` (id_blog_post);
		ALTER TABLE {$this->table_prefix}blog_comments ADD INDEX `aproved` (aproved);
		
EOT;

		$qrs = explode(";", $basicSQL);
		foreach ($qrs as $query) {
			$this->get_db()->query($query);
		}
	}
}
?>