const endpoint = '/api/posts';

export async function get(currentPage, tagId) {
    const response = await axios.get(endpoint, {
        params: {
            page: currentPage,
            tag_id: tagId,
        }
    });
    return response.data;
}
