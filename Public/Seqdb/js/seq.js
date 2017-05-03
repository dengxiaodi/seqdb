$(document).ready(function() {
	$('body').on('click', '.remove-seqresult', function(e){
        var $this = $(this);
        e.preventDefault();

        swal({
            title: '删除测序文库记录',
            text: "删除该测序文库记录后，所有相关的测序结果和数据都将被删除。确认删除样本？",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#9f9f9f',
            confirmButtonText: '确定删除',
            cancelButtonText: '取消'
        }).then(function () {
            window.location.href = $this.attr('href');
        }).catch(swal.noop);
    });
})