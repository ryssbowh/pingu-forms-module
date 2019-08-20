import * as h from 'PinguHelpers';
import dashify from 'dashify';

const Forms = (() => {

	let opt = {
		forms: $('form'),
	};

	function init()
	{
		h.log('Forms initialized');
		if(opt.forms.length){
			initForm(opt.forms)
		}
	}

	function initForm(form)
	{
		if(form.find('input.js-dashify').length){
			bindDashify(form.find('input.js-dashify'));
		}
	}

	function bindDashify(elem)
	{
		let form = elem.closest('form');
		let source = form.find('input[name='+elem.data('dashifyfrom')+']');
		if(!source.length) return;
		source.blur(function(){
			if(elem.val()) return;
			elem.val(dashify(source.val()));
		});
	}

	function getMethod(form)
	{
		var method = form.attr('method');
		if(form.find('input[name=_method]').length){
			method = form.find('input[name=_method]').val();
		}
		return method.toLowerCase();
	}

	return {
		init: init,
		initForm: initForm,
		getMethod: getMethod
	};

})();

export default Forms;