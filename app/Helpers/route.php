<?php

if(! function_exists('prefixActive')){
	function prefixActive($prefixName)
	{
		return	request()->route()->getPrefix() == $prefixName ? 'active' : '';
	}
}

if(! function_exists('prefixBlock')){
	function prefixBlock($prefixName)
	{
		return	request()->route()->getPrefix() == $prefixName ? 'block' : 'none';
	}
}

if(! function_exists('routeActive')){
	function routeActive($routeName)
	{
		return	request()->routeIs($routeName) ? 'active' : '';
	}
  function imageUp($image)
        {
            $ext=$image->getClientOriginalExtension();
            $final_name = rand(111111111,999999999).'.'.$ext;
            $image->move('uploads',$final_name);

            // $request->file('favicon')->move(public_path('uploads'),$final_name);
            return $final_name;

        }

}


