Route::group(['prefix' => 'admin'], function () {
    Route::any('/bread/sort/{table?}/{column?}', [App\Http\Controllers\Vendor\VoyagerController::class, 'sort'])->name('voyager.sort');

    Voyager::routes();
});
