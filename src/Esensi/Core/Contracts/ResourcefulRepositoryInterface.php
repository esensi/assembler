<?php namespace Esensi\Core\Contracts;

/**
 * Resourceful repository interface
 *
 * @author daniel <daniel@bexarcreative.com>
 */
interface ResourcefulRepositoryInterface{

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index();

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes to store on the resource
     * @return object
     */
    public function store(array $attributes);

    /**
     * Display the specified resource.
     *
     * @param integer $id of resource
     * @return object
     */
    public function show($id);

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @param array $attributes to update on the resource
     * @return object
     */
    public function update($id, array $attributes);

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @return boolean
     */
    public function destroy($id);

}