import * as h from 'pingu-helpers';
import dashify from 'dashify';

const Forms = (() => {

	let opt = {
		dashify : $('input.js-dashify')
	};

	function init()
	{
		console.log('Forms initialized');
		if(opt.dashify.length){
			bindDashify(opt.dashify);
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
		getMethod: getMethod
	};

})();

export default Forms;