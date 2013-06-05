<?php
/**
 * Part of the Platform application.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Platform
 * @version    2.0.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Platform\Menus\Models\Menu;

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| Here's a great place to register any custom functions for your
| application. We've included a couple to get you started.
|
*/

if ( ! function_exists('set_menu_order'))
{
	/**
	 * Set the order of the provided menu's children according to
	 * the given array of slugs. This will not remove any menu
	 * items and it will skip non-existent items
	 * (they'll be shoved at the end of the menu).
	 *
	 * @param  string  $menuSlug
	 * @param  array   $slugs
	 * @return void
	 */
	function set_menu_order($menuSlug, array $slugs)
	{
		$previous = null;

		foreach ($slugs as $slug)
		{
			if ( ! $current = Menu::find($slug)) continue;

			// If we have a previous menu child, we're assigning
			// this child as it's next sibling
			if ($previous)
			{
				$previous->refresh();
				$current->makeNextSiblingOf($previous);
			}

			// Otherwise, we're on the first child in the
			// loop, at which point we want our child to
			// be the first child
			else
			{
				$admin = Menu::find($menuSlug);
				$current->makeFirstChildOf($admin);
			}

			$previous = $current;
		}
	}
}

if ( ! function_exists('show_error_page'))
{
	/**
	 * Show a production error page for the given status code.
	 *
	 * @param  int  $statsuCode
	 * @return Illuminate\Http\Response
	 */
	function show_error_page($statusCode)
	{
		try
		{
			// Firstly we'll try make a view for the status code.
			// The default theme ships with these views, but just
			// for safety (in-case the theme system is what's
			// causing the error) we also include duplicate
			// views under app/views. Pretty foolproof.
			$string = View::make("errors/$statusCode");
		}
		catch (Exception $e)
		{
			// If we got an exception thrown in the process of
			// loading the error view and our status code is
			// not 500, the view probably doesn't exist. So
			// we don't leave the users hanging, we'll
			// attempt to show a 500 error page.
			if ($statusCode != 500)
			{
				return show_error_page(500);
			}

			// However, if we got this far, we'll simply
			// return a string which lets the user know
			// something's horribly wrong. This is
			// basically worst-case scenario.
			$string = '500 Internal Server Error';
		}

		return Response::make($string, $statusCode);
	}
}
