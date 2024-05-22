<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class ListingController
{
	protected $db;

	public function __construct()
	{
		$config = require basePath('config/db.php');
		$this->db = new Database($config);
	}
	/**
	 * Show all listings
	 * 
	 * @return void
	 */

	public function index()
	{
		$listings = $this->db->query('SELECT * FROM listings')->fetchAll();

		loadView('listings/index', [
			'listings' => $listings
		]);
	}
	/**
	 * Show the form to create a new listing
	 * 
	 * @return void
	 */
	public function create()
	{
		loadView('listings/create');
	}
	/**
	 * Show a single listing
	 * 
	 * @return void
	 */
	public function show($params)
	{
		$id = $params['id'] ?? '';

		$params = [
			'id' => $id
		];

		$listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

		// Check if listing exists
		if (!$listing) {
			ErrorController::notFound('Job offer not found');
			return;
		}

		loadView('listings/show', [
			'listing' => $listing
		]);
	}

	/**
	 * Store data from form in db
	 * 
	 * @return void
	 */
	public function store()
	{
		$allowedFileds = [
			'title',
			'description',
			'salary',
			'tags',
			'company',
			'address',
			'city',
			'state',
			'phone',
			'email',
			'requirements',
			'benefits',
		];

		$newListingData = array_intersect_key($_POST, array_flip($allowedFileds));

		$newListingData['user_id'] = 1;

		$newListingData = array_map('sanitize', $newListingData);

		inspectAndDie($newListingData);
	}
}
