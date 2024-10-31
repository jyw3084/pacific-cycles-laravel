function initSort() {
    Sortable.init();
}
$('.grid-expand a').on('click', function(){
    setTimeout(initSort, 1000);
});
