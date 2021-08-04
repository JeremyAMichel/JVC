let page=1;
let content = document.querySelector('.scrollBottom');
let isFetching = false;
let hasMoreData = true;


window.onscroll = function() {
    let d = document.documentElement;
    let offset = d.scrollTop + window.innerHeight;
    let height = d.offsetHeight;
    if (offset >= height) {
        if(hasMoreData=== true){
            if(isFetching === false){
                fetchData(); 
            }  
        }
        
    }
};

function fetchData(){
    isFetching= true;
    fetch('/test-ajax?page='+page)
        .then(result => result.json())
        .then((data) =>{
            console.log(data);
            content.insertAdjacentHTML('beforeend', data.html);
            isFetching=false;
            if(data.html===''){
                hasMoreData=false;
            }
        });
    page ++;
}
fetchData();