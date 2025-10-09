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

export const fetchBiller = async(data) => {
    try{
        const res = await axios.post('/bbps/biller', data)
        return res;
    }catch(err){
        console.error('Something went wrong for fetching bill details',err);
        throw err;        
    }
}

export const fetchPayableAmount = async (data) => {
    try {
      const response = await axios.post(`/bbps/fetchbill`, data);
      return response;
    } catch (error) {
      console.error('Error fetching bill:', error);
      throw error;
    }
};

export const payamount = async (data) => {
    try{
        const response = await axios.post('/bbps/billpayment', data)
        return response;
    }catch(err){
        console.log('Error in payment', err);
        throw err;
    }
}

export const getOperators = async () => {
    try{
        const res = await axios.get('/recharge/operator');
        return res;
    }catch(err){
        console.error('Something went wrong for fetch operator', err)
        throw err;
    }
}

export const doRecharge = async (data) => {
    try{
        const res = await axios.post('/recharge/dorecharge', data);
        return res;
    }catch(err){
        console.error('Something went wrong to Recharge', err);
        throw err;
    }
}