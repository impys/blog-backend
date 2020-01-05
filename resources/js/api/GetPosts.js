const endpoint = '/api/posts';

export async function get() {
    const response = await axios.get(endpoint);
    return response.data;
}
