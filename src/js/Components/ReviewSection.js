
import * as Utilities from '../helpers/utilities.js';
import React from 'react';
import PropTypes from 'prop-types';
import Review from './Review.js';
import Pagination from './Pagination.js';

/* eslint-disable no-undef */
const postID = lnarchiveVariables.object_id;
const wpRequestURL = lnarchiveVariables.wp_rest_url+'wp/v2/';
const customAPIRequestURL = lnarchiveVariables.custom_api_url;
const userNonce = lnarchiveVariables.nonce;
const commentsPerPage = lnarchiveVariables.per_page;
/* eslint-enable no-undef */

/**
 * Represents a section for displaying and submitting reviews or comments for a post.
 * @param {object} props - The component props
 * @param {number} props.commentsCount - The total number of comments for the post
 * @param {boolean} props.isLoggedIn - Indicates if the user is currently logged in
 * @param {number} props.maxProgress - The maximum number of volumes that can be selected when submitting a review
 * @param {number} props.userID - The ID of the currently logged-in user
 * @param {string} props.commentType - The type of comments to display (e.g. "review")
 * @param {string} props.loginURL - The URL for the login page
 * @return {JSX.Element} - The ReviewSection component
 */
export default function ReviewSection(props) {
    const [sectionInfo, updateSectionInfo] = React.useState({
        comment_list: [],
        commentsCount: props.commentsCount,
        pagination: null,
        pagination_display: false,
        current_page: 1,
        current_sort: 'likes',
        review_content: '',
        progress: 0,
    });

    const isLoggedIn = props.isLoggedIn;
    const userID = props.userID;
    const commentType = props.commentType.charAt(0).toUpperCase() + props.commentType.slice(1);

    const fetchComments = async () => {
        const fields = '&_fields=id,author_name,author,author_avatar_urls,content,date,post,userID,meta,is_logged_in,user_comment_response,rating';

        const res = await fetch( `${wpRequestURL}comments?post=${postID}&orderby=${sectionInfo.current_sort}&per_page=${commentsPerPage}&page=${sectionInfo.current_page}${fields}`, {
            headers: {
                'X-WP-Nonce': userNonce,
            },
        });
        const data= await res.json();

        if ( res.status === 200 ) {
            const commentsMap = data.map( (comment) => {
                return (
                    <Review
                        key={comment.id}
                        isLoggedIn={isLoggedIn}
                        userID={userID}
                        deleteReview={deleteReview}
                        maxProgress={props.maxProgress}
                        {...comment}
                    />
                );
            });

            updateSectionInfo( (prevInfo) => ( {
                ...prevInfo,
                comment_list: commentsMap,
                pagination: <Pagination currentPage={sectionInfo.current_page} length={Math.ceil(sectionInfo.commentsCount/commentsPerPage)} handleclick={handlePageSelect}></Pagination>,
            }));
        }
    };

    React.useMemo( function() {
        fetchComments( sectionInfo.current_sort, sectionInfo.current_page);
    }, [sectionInfo.current_page, sectionInfo.current_sort, sectionInfo.commentsCount]);

    const submitReview = async (event) => {
        event.preventDefault();

        if (sectionInfo.review_content == '') {
            return;
        }

        const res = await fetch( `${customAPIRequestURL}submit_comment`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': userNonce,
            },
            body: JSON.stringify({
                content: Utilities.esc_html(sectionInfo.review_content),
                postID: postID,
                progress: sectionInfo.progress,
            }),
        });

        if (res.status === 201) {
            updateSectionInfo( (prevInfo) => ({
                ...prevInfo,
                commentsCount: ++prevInfo.commentsCount,
                current_sort: 'date',
                review_content: '',
            }));
        }
    };

    const handleChange = (event) => {
        const {name, value} = event.target;

        updateSectionInfo( (prevInfo) => ({
            ...prevInfo,
            [name]: value,
        }));
    };

    const handlePageSelect = (event) => {
        updateSectionInfo( (prevInfo) => ({
            ...prevInfo,
            current_page: parseInt(event.target.value),
        }));
        document.getElementById('reviews-form').scrollIntoView(true);
    };

    const deleteReview = async (id) => {
        if ( !window.confirm('Are you sure you want to delete your Review?')) {
            return;
        }

        await fetch( `${wpRequestURL}comments/${id}`, {
            method: 'DELETE',
            headers: {
                'X-WP-Nonce': userNonce,
            },
        });

        updateSectionInfo( (prevInfo) => ({
            ...prevInfo,
            commentsCount: prevInfo.commentsCount-1,
        }));
    };

    return (
        <>
            <h2 className="d-flex justify-content-center review-title">{commentType+'s'}</h2>
            {
                isLoggedIn ?
                    <form id="reviews-form" className="mb-3" onSubmit={submitReview}>
                        {
                            commentType == 'Review' && props.maxProgress>0 &&
                            <div className="float-end">
                                <label htmlFor="progress"><h5>No of Volumes(Read): </h5></label>
                                <input type="number" id="progress" name="progress" value={sectionInfo.progress} onChange={handleChange} min="0" max={props.maxProgress}/>
                            </div>
                        }
                        <h4 className="float-start">Write your {commentType}</h4>
                        <textarea name="review_content" id="review_content" onChange={handleChange} value={sectionInfo.review_content}/>
                        <div className="d-flex justify-content-end">
                            <button className="px-3 py-2" id="review-submit">Submit</button>
                        </div>
                    </form> :
                    <h3>You need to be <a href={props.loginURL}>logged in</a> to submit a {commentType}</h3>
            }
            {
                sectionInfo.commentsCount>0 &&
                <div id="reviews-filter-header" className="d-flex justify-content-end">
                    <label htmlFor="review-filter" className="me-1">Sort:</label>
                    <select name="current_sort" id="review-filter" onChange={handleChange} value={sectionInfo.current_sort}>
                        {isLoggedIn && <option value="author">Your {commentType}s</option>}
                        <option value="likes">Popularity</option>
                        <option value="date">Newest</option>
                        {props.maxProgress >0 && <option value="progress">Progress</option>}
                    </select>
                </div>
            }
            <div id="reviews-list" className="ps-0">
                {sectionInfo.comment_list}
                {sectionInfo.pagination}
            </div>
        </>
    );
}

ReviewSection.propTypes = {
    commentsCount: PropTypes.number.isRequired,
    isLoggedIn: PropTypes.bool.isRequired,
    maxProgress: PropTypes.number.isRequired,
    userID: PropTypes.number.isRequired,
    commentType: PropTypes.string.isRequired,
    loginURL: PropTypes.string.isRequired,
};

ReviewSection.defaultProps = {
    isLoggedIn: false,
    commentType: 'comment',
    commentsCount: 0,
    maxProgress: 0,
};
