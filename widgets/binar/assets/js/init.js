var collect={
    runner:[],
    counter:0,
    def : null,
    countDone:function(){
        --collect.counter;
        if (collect.counter == 0) {
            collect.def.resolve();
        }
        },
    run:function(setup){
        this.def = jQuery.Deferred();
        this.counter = setup.length;
        $.each(setup,function(index,obj){
            if (typeof(obj) == 'object') {
                collect.runner.push($.get.apply($.get,obj).done(collect.countDone));
            } else {
                if (typeof(obj) == 'function') {
                    collect.countDone();
                }
            }
        });
        return this.def.promise();
    },
    clear:function (){
        this.runner = [];
        this.def = null;
    }
};

function initTrees() {
    var widgets = $(".binar");
    var arr =[];
    widgets.each(function () {
        var treeName = this.id;
        window[treeName] = Object.create(BINAR_TREE);
        var tmp = window[treeName];
        arr.push(tmp.init(treeName));
    });
    collect.run(arr);
}

