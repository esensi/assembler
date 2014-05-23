<?php namespace Esensi\Core\Traits;

/**
 * Trait implementation of resource controller interface
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see \Esensi\Core\Contracts\ResourceControllerInterface
 */
trait ResourceControllerTrait {

	/**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        // Get the paginator using the parent API
        $paginator = parent::index();

        // Show collection as a paginated table
        $collection = $paginator->getCollection();
        $this->content('index', compact('paginator', 'collection'));
    }

    /**
     * Display a create form for the specified resource.
     *
     * @return void
     */
    public function create()
    {
        // Get the form options
        $options = method_exists($this, 'formOptions') ? $this->formOptions() : [];

        // Render create view
        $this->content( 'create', $options);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function store()
    {
        // Use the parent API to save the resource
        $object = parent::store();

        // Redirect back with message
        return $this->redirect('created', ['id' => $object->id])
            ->with('message', $this->message('created') );
    }

    /**
     * Display the specified resource.
     *
     * @param integer $id of resource
     * @return void
     */
    public function show($id)
    {
        // Get the resource using the parent API
        $object = parent::show($id);

        // Render show view
        $this->content( 'show', [ $this->package => $object ] );
    }

    /**
     * Display an edit form for the specified resource.
     *
     * @param integer $id of resource
     * @return void
     */
    public function edit($id)
    {
        // Get the resource
        $object = parent::show($id);

        // Get the form options
        $options = method_exists($this, 'formOptions') ? $this->formOptions($object) : [ $this->package => $object ];

        // Render edit view
        $this->content( 'edit', $options );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param integer $id of resource to update
     * @return \Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        // Use the parent API to update the resource
        $object = parent::update($id);

        // Redirect back with message
        return $this->back('updated', ['id' => $object->id])
            ->with('message', $this->message('updated') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id of resource to remove
     * @return \Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        // Use the parent API to remove the resource
        $response = parent::delete($id);

        // Redirect back with message
        return $this->redirect( 'deleted' )
            ->with('message', $this->message('deleted') );
    }

    /**
     * Alias for delete method
     *
     * @param integer $id of resource to remove
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        return $this->delete($id);
    }

}