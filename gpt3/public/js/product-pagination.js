var page = 2;
var limit = 12;

async function getLoadData(url, page, limit) {
    const response = await fetch(
        `${
            url == "/" ? "load-data" : `${url}`
        }?page=${page}&limit=${limit}&ajax=true`
    );
    const data = await response.json();
    return data;
}

// Selecting The Container.
const container = document.querySelector("#pLists");
// The Scroll Event.
window.addEventListener("scroll", () => {
    const { scrollHeight, scrollTop, clientHeight } = document.documentElement;
    if (scrollTop + clientHeight > scrollHeight - 5) {
        setTimeout(loadData, 2000);
    }
});

// The load data function creates The HTML for the product list.
// It append it to the container.
$(".spinner").hide();
function loadData() {
    let url = window.location.pathname;
    console.log(url);

    // $(".spinner").show();
    // var segment_str = window.location.pathname; // return segment1/segment2/segment3/segment4
    // var segment_array = segment_str.split("/");

    page++;
    getLoadData(url, page, limit).then((response) => {
        // $(".spinner").hide();
        $("#pLists").append(response.html);
    });
}
