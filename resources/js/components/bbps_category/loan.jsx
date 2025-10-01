"use client"

import { useState } from "react"
import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Lightbulb, Zap, Receipt, AlertCircle, ArrowLeft } from "lucide-react"
import Layout from "../layout"
import { Link } from "@inertiajs/react"
import { fetchBiller, fetchPayableAmount } from "@/lib/apis"

const recentBills = [
  { id: "ELEC001", provider: "SEB", amount: "$85", dueDate: "2024-01-25", status: "Paid", consumerNo: "123456789" },
  { id: "ELEC002", provider: "DEB", amount: "$92", dueDate: "2024-01-28", status: "Pending", consumerNo: "987654321" },
  { id: "ELEC003", provider: "MP", amount: "$78", dueDate: "2024-01-20", status: "Overdue", consumerNo: "456789123" },
  { id: "ELEC004", provider: "BE", amount: "$120", dueDate: "2024-02-01", status: "Pending", consumerNo: "654987321" },
  { id: "ELEC005", provider: "CP", amount: "$110", dueDate: "2024-02-05", status: "Paid", consumerNo: "321654987" },
  { id: "ELEC006", provider: "SEB", amount: "$90", dueDate: "2024-02-10", status: "Pending", consumerNo: "111222333" },
  { id: "ELEC007", provider: "DEB", amount: "$75", dueDate: "2024-02-12", status: "Paid", consumerNo: "444555666" },
]

export function LoanCategory({ list = [] }) {
  const [searchProvider, setSearchProvider] = useState("")
  const [searchBill, setSearchBill] = useState("")
  const [selectedBillData, setSelectedBillData] = useState(null)
  const [view, setView] = useState("providers") // "providers" or "billData"
  const [isLoading, setIsLoading] = useState(false)
  const [inputValue, setInputValue] = useState("")
  const [inputError, setInputError] = useState("")
  const [payableAmountLoading, setPayableAmountLoading] = useState(false)
  const [payableAmountError, setPayableAmountError] = useState("")
  const [payableData, setPayableData] = useState(null)

  // 🔹 Filter Providers (handle undefined safely)
  const filteredProviders = list.filter(
    (provider) =>
      provider &&
      ((provider.billerName || "").toLowerCase().includes(searchProvider.toLowerCase()) ||
      (provider.billerId || "").toLowerCase().includes(searchProvider.toLowerCase()) ||
      (provider.billerCoverage || "").toLowerCase().includes(searchProvider.toLowerCase()))
  )

  // 🔹 Filter Bills
  const filteredBills = recentBills.filter(
    (bill) =>
      (bill.consumerNo || "").toLowerCase().includes(searchBill.toLowerCase()) ||
      (bill.provider || "").toLowerCase().includes(searchBill.toLowerCase()) ||
      (bill.status || "").toLowerCase().includes(searchBill.toLowerCase())
  )

  const handleClick = async (billerName) => {
    setIsLoading(true)
    try {
      const response = await fetchBiller({ 'blr_name': billerName })
      setSelectedBillData(response.data.billdata)
      setView("billData")
    } catch (error) {
      console.error('Error fetching bill data:', error)
      // Optionally show error toast/message
    } finally {
      setIsLoading(false)
    }
  }

  const handleBack = () => {
    setView("providers")
    setSelectedBillData(null)
  }

  // 🔹 Input Validation
  const validateInput = (value, paramInfo) => {
    if (!paramInfo) return ""
    const { minLength, maxLength, regEx, isOptional } = paramInfo
    if (!value && isOptional === "false") {
      return `${paramInfo.paramName} is required`
    }
    if (value.length < parseInt(minLength)) {
      return `${paramInfo.paramName} must be at least ${minLength} characters`
    }
    if (value.length > parseInt(maxLength)) {
      return `${paramInfo.paramName} must not exceed ${maxLength} characters`
    }
    if (regEx && !new RegExp(regEx).test(value)) {
      return `${paramInfo.paramName} is invalid`
    }
    return ""
  }

  const handleInputChange = (e) => {
    const value = e.target.value
    setInputValue(value)
    if (selectedBillData?.billerInputParams?.paramInfo) {
      const error = validateInput(value, selectedBillData.billerInputParams.paramInfo)
      setInputError(error)
    }
  }

  const handleGetPayableAmount = async () => {
  if (!selectedBillData?.billerInputParams?.paramInfo) {
    setPayableAmountError("No input parameters available")
    return
  }

  const paramInfo = selectedBillData.billerInputParams.paramInfo
  const error = validateInput(inputValue, paramInfo)
  if (error) {
    setInputError(error)
    return
  }

  setPayableAmountLoading(true)
  setPayableAmountError("")
  try {
    const response = await fetchPayableAmount({
      billerId: selectedBillData.billerId,
      paramName: paramInfo.paramName,
      paramValue: inputValue,
    })

    console.log("Payable amount response:", response.data)
    setPayableData(response.data.billData)

  } catch (error) {
    console.error("Error fetching payable amount:", error)
    setPayableAmountError("Failed to fetch payable amount")
  } finally {
    setPayableAmountLoading(false)
  }
}


  return (
    <Layout>
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
        className="space-y-6"
      >
        <div>
          <h1 className="text-3xl font-bold text-foreground mb-2">Loan Repayment</h1>
          <p className="text-muted-foreground">Pay electricity bills for various providers</p>
        </div>

        {/* Summary Cards */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium">Total Payments</CardTitle>
              <Lightbulb className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">$12,450</div>
              <p className="text-xs text-muted-foreground">This month</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium">Bills Paid</CardTitle>
              <Receipt className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">156</div>
              <p className="text-xs text-muted-foreground">This month</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium">Pending Bills</CardTitle>
              <Zap className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">23</div>
              <p className="text-xs text-muted-foreground">Due soon</p>
            </CardContent>
          </Card>

          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium">Overdue</CardTitle>
              <AlertCircle className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">8</div>
              <p className="text-xs text-muted-foreground text-red-600">Needs attention</p>
            </CardContent>
          </Card>
        </div>

        {/* Providers + Bills */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          {/* Providers Section */}
          <Card>
            <CardHeader>
              <CardTitle>
                {view === "providers" ? "Available Electricity Providers" : "Bill Details"}
              </CardTitle>
              <CardDescription>
                {view === "providers" ? "List of supported electricity providers" : "Details of the selected provider's bill"}
              </CardDescription>
            </CardHeader>
            <CardContent>
              {view === "providers" ? (
                <>
                  <Input
                    placeholder="Search by Name / ID / Coverage"
                    value={searchProvider}
                    onChange={(e) => setSearchProvider(e.target.value)}
                    className="mb-4"
                  />
                  {list.length === 0 ? (
                    <p className="text-sm text-muted-foreground">No providers available.</p>
                  ) : filteredProviders.length === 0 ? (
                    <p className="text-sm text-muted-foreground">No providers match your search.</p>
                  ) : (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[400px] overflow-y-auto">
                      {filteredProviders.map((provider) => (
                        <div
                          key={provider.blr_id}
                          className="p-4 border rounded-lg cursor-pointer hover:bg-gray-100"
                          onClick={() => handleClick(provider.blr_name)}
                        >
                          <h3 className="font-medium">{provider.blr_name}</h3>
                          <p className="text-sm text-muted-foreground">{provider.billerCategory}</p>
                          <p className="text-sm text-muted-foreground">{provider.billerCoverage}</p>
                        </div>
                      ))}
                    </div>
                  )}
                </>
              ) : (
                <div>
                  <button
                    onClick={handleBack}
                    className="flex items-center text-sm text-blue-600 hover:underline mb-4"
                  >
                    <ArrowLeft className="h-4 w-4 mr-1" /> Back to Providers
                  </button>
                  {isLoading ? (
                    <p className="text-sm text-muted-foreground">Loading bill data...</p>
                  ) : selectedBillData ? (
                    <div className="p-4 border rounded-lg">
                      <h3 className="font-medium text-lg">{selectedBillData.billerName}</h3>
                      {selectedBillData.billerInputParams?.paramInfo?.visibility === "true" && (
                        <div className="mt-4">
                          <label className="text-sm font-medium">
                            {selectedBillData.billerInputParams.paramInfo.paramName}
                            {selectedBillData.billerInputParams.paramInfo.isOptional === "false" && (
                              <span className="text-red-600">*</span>
                            )}
                          </label>
                          <Input
                            placeholder={selectedBillData.billerInputParams.paramInfo.paramName}
                            value={inputValue}
                            onChange={handleInputChange}
                            className={`mt-1 ${inputError ? "border-red-500" : ""}`}
                            maxLength={parseInt(selectedBillData.billerInputParams.paramInfo.maxLength)}
                          />
                          {inputError && (
                            <p className="text-sm text-red-600 mt-1">{inputError}</p>
                          )}
                          {payableData && (
                            <div className="p-4 border rounded-lg mt-4 bg-green-200">
                              <h3 className="font-medium text-lg mb-2">Bill Details</h3>
                              <p><strong>Customer Name:</strong> {payableData.billerResponse?.customerName}</p>
                              <p><strong>Bill Number:</strong> {payableData.billerResponse?.billNumber}</p>
                              <p><strong>Bill Date:</strong> {payableData.billerResponse?.billDate}</p>
                              <p className="text-red-500"><strong>Due Date:</strong> {payableData.billerResponse?.dueDate}</p>
                              <p><strong>Bill Amount:</strong> ₹{payableData.billerResponse?.billAmount / 100}</p>

                              {/* Additional Info */}
                              {/* {payableData.additionalInfo?.info?.length > 0 && (
                                <div className="mt-2">
                                  <h4 className="font-medium">Additional Info:</h4>
                                  <ul className="list-disc pl-4">
                                    {payableData.additionalInfo.info.map((item, i) => (
                                      <li key={i}>
                                        {item.infoName}: {item.infoValue}
                                      </li>
                                    ))}
                                  </ul>
                                </div>
                              )} */}
                            </div>
                          )}
                          {payableAmountError && (
                            <p className="text-sm text-red-600 mt-2">{payableAmountError}</p>
                          )}
                          <Button
                            className="w-full mt-6"
                            onClick={handleGetPayableAmount}
                            disabled={payableAmountLoading}
                          >
                            {payableAmountLoading ? "Fetching..." : "Get Payable Amount"}
                          </Button>
                        </div>
                      )}
                    </div>
                  ) : (
                    <p className="text-sm text-muted-foreground">No bill data available.</p>
                  )}
                </div>
              )}
            </CardContent>
          </Card>

          {/* Bills Section */}
          <Card>
            <CardHeader>
              <CardTitle>Recent Bill Payments</CardTitle>
              <CardDescription>Your recent electricity bill payments</CardDescription>
            </CardHeader>
            <CardContent>
              <Input
                placeholder="Search by Consumer No / Provider / Status"
                value={searchBill}
                onChange={(e) => setSearchBill(e.target.value)}
                className="mb-4"
              />
              <div className="space-y-4 max-h-[400px] overflow-y-auto">
                {filteredBills.map((bill) => (
                  <div key={bill.id} className="flex items-center justify-between p-3 border rounded-lg">
                    <div>
                      <p className="font-medium">{bill.consumerNo}</p>
                      <p className="text-sm text-muted-foreground">
                        {bill.provider} • Due: {bill.dueDate}
                      </p>
                    </div>
                    <div className="text-right">
                      <p className="font-medium">{bill.amount}</p>
                      <Badge
                        variant={
                          bill.status === "Paid"
                            ? "default"
                            : bill.status === "Pending"
                            ? "secondary"
                            : "destructive"
                        }
                      >
                        {bill.status}
                      </Badge>
                    </div>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>
        </div>
      </motion.div>
    </Layout>
  )
}