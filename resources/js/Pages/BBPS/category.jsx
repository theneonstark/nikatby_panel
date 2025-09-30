import { usePage } from "@inertiajs/react";
import React from "react";

export default function CategoryDetail({ category, services, hasStates, states }) {
  const renderLayout = () => {
    const { url } = usePage(); // pura url aata hai
  const category = decodeURIComponent(url.split("/").pop()); // last part pick karo
    switch (category) {
      case "Electricity":
        return (
          <div className="p-6 bg-yellow-50 rounded-xl shadow">
            <h2 className="text-2xl font-bold mb-4">Pay Electricity Bills</h2>
            
            <ul className="mt-4">
              
            </ul>
          </div>
        );

      case "Water":
        return (
          <div className="p-6 bg-blue-50 rounded-xl shadow">
            <h2 className="text-2xl font-bold mb-4">Water Utility Payments</h2>
            <ul>
              
            </ul>
          </div>
        );

      case "Insurance":
        return (
          <div className="p-6 bg-green-50 rounded-xl shadow">
            <h2 className="text-2xl font-bold mb-4">Insurance Premiums</h2>
            <p>Select your provider:</p>
            <ul>
              
            </ul>
          </div>
        );

      // Add more cases for each service
      default:
        return (
          <div className="p-6 bg-gray-50 rounded-xl shadow">
            <h2 className="text-xl font-semibold mb-4">{category} Services</h2>
            <ul>
             
            </ul>
          </div>
        );
    }
  };

  return <div>{renderLayout()}</div>;
}
