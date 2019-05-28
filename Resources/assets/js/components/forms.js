import * as h from 'pingu-helpers';

const Forms = (() => {

	function getMethod(form){
		var method = form.attr('method');
		if(form.find('input[name=_method]').length){
			method = form.find('input[name=_method]').val();
		}
		return method.toLowerCase();
	}

	return {
		getMethod: getMethod
	};

})();

export default Forms;