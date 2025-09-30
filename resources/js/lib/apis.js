const csrfToken = document.head.querySelector('meta[name="csrf-token"]')?.content;

export const signup = async (data) => {
    try {
        const response = await axios.post('/auth/onboard', data);
        return response;
    }catch(err){
        console.error('Failed To Register', err)
        throw err;
    }
}

export const Login = async (data) => {
    try {
        const response = await axios.post('/auth/check', {
                _token: csrfToken, // Automatically include the CSRF token
                ...data
        });
        return response; // Directly returning the data
    } catch (error) {
        console.error('Error fetching business statistics:', error);
        throw error; // Re-throw the error for handling in the component
    }
};

export const logout = async() =>{
    try{
        const res = await axios.get('auth/logout');
    }catch(Err){
        console.error('Something Went Wrong', Err)
        throw Err;
    }
}