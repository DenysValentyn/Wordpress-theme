
import React from 'react';
import PropTypes from 'prop-types';

/**
 * Pagination Component
 * 
 * @param {Object} props - Component props
 * @param {number} props.current_page - Current page number
 * @param {number} props.length - Total number of pages
 * @param {number} [props.siblings=2] - Number of sibling pages to show on either side of the current page
 * @param {function} props.handleclick - Function to handle button click events
 * 
 * @returns {JSX} - Component JSX
 */
export default function Pagination(props) { 
    //Local Variables
    let pagination=[]; //store all pages which are to be rendered
    let current_page = props.current_page; //current page
    let length = props.length; //tital number of pages
    let siblings = props.siblings; //siblings to both sides of the current page
    let start = current_page-siblings > 1 ? current_page-siblings: 1; //first page to render
    let end = current_page+siblings > length ? length : current_page+siblings; //last page to render

    while( start<=end){ //Render all the pages
        pagination.push(
            <button key={start} value={start} onClick={props.handleclick} className={ start==current_page ? "current" : undefined}>{start}</button>
        );
        start++;
    }

    if( current_page-siblings>1 ) //Render the back to first button if we are not on starting pages
        pagination=[
            <button key='1' value='1' onClick={props.handleclick}>{'<<'}</button>,
            <button key='...'>{'...'}</button>,
            ...pagination,
        ];
    
    if( current_page+siblings<length ) //Render to the last button if we are not on last pages
        pagination=[
            ...pagination,
            <button key='....'>{'...'}</button>,
            <button key={length} value={length} onClick={props.handleclick}>{'>>'}</button>,
        ];
    
    return( //Render the Pagination
        <>{   
            length>1 && //If there are more than one pages then render
            <div className="page-list">
                {pagination}
            </div>
        }</>
    );
}

//Default Prop Types
Pagination.propTypes = {
  current_page: PropTypes.number.isRequired,
  length: PropTypes.number.isRequired,
  handleclick: PropTypes.func.isRequired,
  siblings: PropTypes.number
};

//Default Prop Values
Pagination.defaultProps ={
    siblings: 2,
}