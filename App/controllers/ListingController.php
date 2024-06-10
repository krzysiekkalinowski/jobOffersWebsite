<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Session;
use Framework\Validation;
use Framework\Authorization;

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
	 * @param array $params
	 * @return void
	 */

	public function index()
	{
		$listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();

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
			'voivodeship',
			'phone',
			'email',
			'requirements',
			'benefits',
		];

		$newListingData = array_intersect_key($_POST, array_flip($allowedFileds));

		$newListingData['user_id'] = Session::get('user')['id'];

		$newListingData = array_map('sanitize', $newListingData);

		$reauiredFields = [
			'title',
			'description',
			'salary',
			'email',
			'city',
			'voivodeship',
		];

		$error = [];
		foreach ($reauiredFields as $field) {
			if (empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
				$errors[$field] = ucfirst($field) . ' is required';
			}
		};

		if (!empty($errors)) {
			// Reload the form with errors
			loadView('listings/create', [
				'errors' => $errors,
				'listing' => $newListingData
			]);
		} else {
			//Submit forms data

			$fields = [];

			foreach ($newListingData as $key => $value) {
				$fields[] = $key;
			}

			$fields = implode(', ', $fields);

			$values = [];

			foreach ($newListingData as $key => $value) {
				// Convert empty strings to NULL
				if ($value === '') {
					$newListingData[$key] = 'NULL';
				}
				$values[] = ':' . $key;
			}

			$values = implode(', ', $values);

			$query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

			$this->db->query($query, $newListingData);

			Session::setFlashMessage('success_message', 'Listing created');

			redirect('/listings');
		}
	}

	/**
	 * Delete a listing
	 * 
	 * @param array $params
	 * @return void
	 */
	public function destroy($params)
	{
		$id = $params['id'];

		$params = [
			'id' => $id
		];
		$listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();

		// Check if listing exists
		if (!$listing) {
			ErrorController::notFound('Job offer not found');
			return;
		}

		//Authorize user
		if (!Authorization::isOwner($listing['user_id'])) {
			Session::setFlashMessage('error_message', 'You are not authorized to delete this listing');
			return redirect('/listings/' . $listing['id']);
		}

		$this->db->query('DELETE FROM listings WHERE id = :id', $params);

		//Set flash message
		Session::setFlashMessage('success_message', 'Listing deleted');
		redirect('/listings');
	}
	/**
	 * Show listing edit form
	 * 
	 * @return void
	 */
	public function edit($params)
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

		loadView('listings/edit', [
			'listing' => $listing
		]);
	}

	/**
	 * Update a listing
	 * 
	 * @param array $params
	 * @return void
	 */
	public function update($params)
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

		if (!Authorization::isOwner($listing['user_id'])) {
			Session::setFlashMessage('error_message', 'You are not authorized to edit this listing');
			return redirect('/listings/' . $listing['id']);
		}

		$allowedFileds = [
			'title',
			'description',
			'salary',
			'tags',
			'company',
			'address',
			'city',
			'voivodeship',
			'phone',
			'email',
			'requirements',
			'benefits',
		];

		$updatedValues = [];

		$updatedValues = array_intersect_key($_POST, array_flip($allowedFileds));

		$updatedValues = array_map('sanitize', $updatedValues);

		$requriedFields = [
			'title',
			'description',
			'salary',
			'email',
			'city',
			'voivodeship',
		];

		$errors = [];

		foreach ($requriedFields as $field) {
			if (empty($updatedValues[$field]) || !Validation::string($updatedValues[$field])) {
				$errors[$field] = ucfirst($field) . ' is required';
			}
		}

		if (!empty($errors)) {
			loadView('listings/edit', [
				'errors' => $errors,
				'listing' => $updatedValues
			]);
			exit;
		} else {
			$updateFields = [];

			foreach (array_keys($updatedValues) as $field) {
				$updateFields[] = "{$field} = :{$field}";
			}

			$updateFields = implode(', ', $updateFields);

			$updateQuery = "UPDATE listings SET {$updateFields} WHERE id = :id";

			$updatedValues['id'] = $id;
			$this->db->query($updateQuery, $updatedValues);

			//Set flash message
			Session::setFlashMessage('success_message', 'Listing updated');
			redirect('/listings');

			redirect('/listings/' . $id);
		}
	}
};
