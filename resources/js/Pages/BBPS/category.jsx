import BillCategory from "@/components/bbps_category/billcategory";
import { usePage } from "@inertiajs/react";
import React from "react";

export default function CategoryDetail({ services }) {
  const { url } = usePage(); // pura url aata hai
  const category = decodeURIComponent(url.split("/").pop()); // last part pick karo

  return (
    <BillCategory list={services} title={category} />
  );
}
