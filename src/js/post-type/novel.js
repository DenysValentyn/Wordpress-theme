
//get the Window information
var site_url = window.location.origin;
var json_request_url = site_url.concat("/wordpress/wp-json/wp/v2/");
var path = window.location.pathname;
var slug = path.substring(0, path.length-1);
slug = slug.substring(slug.lastIndexOf("/")+1);

//Get the post information
var post_type = "novel";
var post_id = document.querySelector('.main-row').getAttribute('id');

ReactDOM.render( <h2> Test </h2>,document.getElementById("reviews-list") );

const comments_div = document.getElementById("reviews-list");

fetch( json_request_url+"comments?post="+post_id )
    .then( res => res.json())
    .then( data => {
        console.log(post_id)
        console.log(data)
    })


let format_buttons = document.getElementsByClassName("format_button"); //Get all the format buttons

for( var i=0; i<format_buttons.length; i++){ //Loop through all the possible format buttons
    format_buttons[i].addEventListener('click', function() { //Listen to click event
        document.getElementById("ISBN_info_value").innerHTML = "<a>"+this.getAttribute("isbn")+"</a>"; //Update the Volume ISBN for format
        document.getElementById("Publication Date_info_value").innerHTML = "<a>"+this.getAttribute("publication_date")+"</a>"; //Update the Volume Publication Date for format
    });
}

/*
    Volumes Information Update
*/

let volumes_list = document.getElementsByClassName("volume-link"); //Get all the volumes of the novel

for( var i=0; i<volumes_list.length; i++){ //Loop through all the volumes

    volumes_list[i].addEventListener('click', function() { //Listen to the click event on the volumes

        fetch( json_request_url+"volumes/"+this.id+"?_embed&_fields=title,excerpt,featured_media,_links,meta" ) //Fetch the JSON data
            .then( res => res.json()) //The fetch API Response
            .then( data => { //The fetch api data
                
                document.querySelector(".novel-cover").srcset=data._embedded['wp:featuredmedia']['0'].source_url; //Update the Novel Cover
                document.querySelector(".page-title").innerHTML = data.title.rendered; //Update the Novel Title
                document.getElementById("novel-excerpt").innerHTML = data.excerpt.rendered; //Update the Novel Desc
                
                var novel_terms = data._links['wp:term']; //Get all the taxonomies of the novel

                for( var i=0; i<novel_terms.length; i++) { //Loop through all the taxonomimes

                    var taxonomy_values = data._embedded['wp:term'][i]; //Get Taxonomy terms
                    var element_id = novel_terms[i].taxonomy+"_info_value";
                    const node = document.getElementById(element_id); //Get the taxonomy value parent node

                    if( node == null) //If the node doesnt exist
                    continue;

                    node.innerHTML=""; //Remove all the child elements since new elements will replace them
                    
                    for(var j=0; j<taxonomy_values.length; j++) { //Loop through all the taxonomy terms
                        
                        if( element_id != "format_info_value" ){
                        const tax_val = document.createElement("a"); //Create an anchor elements
                        tax_val.innerText = taxonomy_values[j].name; //Assign the innerText to the anchor tag
                        tax_val.href = taxonomy_values[j].link; //Assign the Link to the anchor tag
                        node.append(tax_val); //Append the anchor tag to the parent node
                        node.append(document.createElement("br")); //Append a line break so the values appear in different lines
                        }
                        else{
                            if( j==0 ){ //The first format values are treated as default values
                                document.getElementById("ISBN_info_value").innerHTML = "<a>"+data.meta["isbn_"+taxonomy_values[j].name+"_value"][0]+"</a>"; //Assign Default ISBN on selection
                                document.getElementById("Publication Date_info_value").innerHTML = "<a>"+data.meta["published_date_value_"+taxonomy_values[j].name][0]+"</a>"; //Assign Default Publication Date on selection
                            }
                            const tax_val = document.createElement("button"); //Create an anchor elements
                            tax_val.innerText = taxonomy_values[j].name; //Assign the insnerText to the anchor tags
                            tax_val.className = taxonomy_values[j].name+"_format format_button"; //ASssign classnames to the buttons
                            tax_val.setAttribute( 'isbn', data.meta["isbn_"+taxonomy_values[j].name+"_value"][0] ); //Store the ISBN data in the button
                            tax_val.setAttribute( 'publication_date', data.meta["published_date_value_"+taxonomy_values[j].name][0] ); //Store the publication date in the button
                            node.append(tax_val); //Append the anchor tag to the parent node
                        }
                    }
                }
                window.scrollTo(0, 0); //Scroll to the top
            })
    }
    );
}

//Novel Info
var volumes_no = document.getElementById("volume-list").children.length;

document.getElementById("volumes-no").innerText= "Volumes - ".concat(volumes_no)  //Update the number of volumes information