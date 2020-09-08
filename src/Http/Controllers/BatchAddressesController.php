<?php

namespace Bazar\Http\Controllers;

use Bazar\Contracts\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;

class BatchAddressesController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Bazar\Contracts\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = Arr::dot($request->except('ids'));

        $data = collect($data)->filter()->mapWithKeys(function ($item, $key) {
            return [str_replace('.', '->', $key) => $item];
        })->all();

        $user->addresses()->whereIn(
            'id', $ids = $request->input('ids', [])
        )->update($data);

        return Redirect::back()->with(
            'message', __(':count address have been updated.', ['count' => count($ids)])
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Bazar\Contracts\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        $user->addresses()->whereIn(
            'id', $ids = $request->input('ids', [])
        )->delete();

        return Redirect::back()->with(
            'message', __(':count addresses have been deleted.', ['count' => count($ids)])
        );
    }
}