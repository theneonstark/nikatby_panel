"use client"

import { useState } from "react"
import { motion } from "framer-motion"
import Layout from "../layout"
import { fetchBiller, fetchPayableAmount, payamount } from "@/lib/apis"
import ProvidersList from "./ProvidersList"
import BillDetails from "./BillDetails"
import RecentBills from "./RecentBills"
import PayDialog from "./PayDialog"
import SummaryCards from "./summarycard"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "../ui/card"

const recentBills = [
  { id: "ELEC001", provider: "SEB", amount: "$85", dueDate: "2024-01-25", status: "Paid", consumerNo: "123456789" },
  { id: "ELEC002", provider: "DEB", amount: "$92", dueDate: "2024-01-28", status: "Pending", consumerNo: "987654321" },
]

const stats = {
    totalPayments: "$12,450",
    billsPaid: 156,
    pendingBills: 23,
    overdue: 8,
  }

export default function BillCategory({ list, title }) {
  const [searchProvider, setSearchProvider] = useState("")
  const [searchBill, setSearchBill] = useState("")
  const [selectedBillData, setSelectedBillData] = useState(null)
  const [view, setView] = useState("providers")
  const [inputValue, setInputValue] = useState("")
  const [payableData, setPayableData] = useState(null)

  // Payment states
  const [showPayModal, setShowPayModal] = useState(false)
  const [isPaying, setIsPaying] = useState(false)
  const [paymentError, setPaymentError] = useState("")
  const [paymentSuccess, setPaymentSuccess] = useState(false)

  // ✅ Fetch biller details
  const handleFetchBiller = async (billerName) => {
    const response = await fetchBiller({ blr_name: billerName })
    setSelectedBillData(response.data.billdata)
    setView("billData")
  }

  // ✅ Fetch payable amount
  const handleFetchPayable = async (billerId, paramName, paramValue) => {
    const response = await fetchPayableAmount({ billerId, paramName, paramValue })
    setPayableData(response.data.billData)
  }

  // ✅ Payment confirm
  const handlePayment = async () => {
    if (!payableData?.billerResponse) return
    setIsPaying(true)
    setPaymentError("")
    try {
      const res = await payamount();
      console.log("Payment success", res)
      setPaymentSuccess(true)
      setTimeout(() => {
        setShowPayModal(false)
      }, 2000)
    } catch (error) {
      setPaymentError("Payment failed. Please try again.")
    } finally {
      setIsPaying(false)
    }
  }

  return (
    <Layout>
      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.5 }} className="space-y-4">
        <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">{title}</h1>
        <p className="text-muted-foreground">Pay bill for various providers</p>
      </div>
      <SummaryCards stats={stats} />
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
      {view === "providers" ? (
          <Card>
            <CardHeader>
              <CardTitle>
                {view === "providers" ? `Available ${title} Providers` : "Bill Details"}
              </CardTitle>
              <CardDescription>
                {view === "providers" ? "List of supported providers" : "Details of the selected provider's bill"}
              </CardDescription>
            </CardHeader>
            <CardContent>
            <ProvidersList
              providers={list}
              searchProvider={searchProvider}
              setSearchProvider={setSearchProvider}
              onSelectProvider={handleFetchBiller}
            />
            </CardContent>
          </Card>
        ) : (
          <BillDetails
            selectedBillData={selectedBillData}
            inputValue={inputValue}
            setInputValue={setInputValue}
            payableData={payableData}
            onBack={() => setView("providers")}
            onFetchPayable={handleFetchPayable}
            onPay={() => setShowPayModal(true)}
          />
        )}
          <Card>
            <CardHeader>
                  <CardTitle>Recent Bill Payments</CardTitle>
                  <CardDescription>Your recent bill payments</CardDescription>
                </CardHeader>
            <CardContent>
              <RecentBills bills={recentBills} searchBill={searchBill} setSearchBill={setSearchBill} />
            </CardContent>
          </Card>
        </div>


        <PayDialog
          open={showPayModal}
          onClose={setShowPayModal}
          payableData={payableData}
          isPaying={isPaying}
          paymentError={paymentError}
          paymentSuccess={paymentSuccess}
          onConfirmPay={handlePayment}
        />
      </motion.div>
      <div className='flex items-center justify-center gap-2 opacity-70 mt-4'>
          <img src="/company/logo.png" alt="" className='w-20 h-full'/>
          <p className='text-xl text-gray-500 font-extralight'>|</p>
          <img src="/company/bharatconnect.png" alt="" className='w-16 h-full'/>
        </div>
    </Layout>
  )
}
