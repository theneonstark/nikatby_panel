"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Badge } from "@/components/ui/badge"
import { Search, Clock, CheckCircle, XCircle, AlertCircle } from "lucide-react"

const enquiryTypes = [
  "Mobile Recharge",
  "DTH Recharge",
  "Electricity Bill",
  "Water Bill",
  "Gas Bill",
  "Insurance Premium",
  "FASTag Recharge",
  "Bus Booking",
  "Train Booking",
  "Flight Booking",
]

const recentEnquiries = [
  {
    id: "TXN001",
    type: "Mobile Recharge",
    amount: "$25",
    status: "Success",
    date: "2024-01-15 10:30 AM",
    refNo: "REF123456789",
  },
  {
    id: "TXN002",
    type: "Electricity Bill",
    amount: "$85",
    status: "Pending",
    date: "2024-01-14 02:15 PM",
    refNo: "REF987654321",
  },
  {
    id: "TXN003",
    type: "Bus Booking",
    amount: "$45",
    status: "Failed",
    date: "2024-01-13 09:45 AM",
    refNo: "REF456789123",
  },
  {
    id: "TXN004",
    type: "FASTag Recharge",
    amount: "$50",
    status: "Success",
    date: "2024-01-12 04:20 PM",
    refNo: "REF789123456",
  },
]

const getStatusIcon = (status) => {
  switch (status) {
    case "Success":
      return <CheckCircle className="w-4 h-4 text-green-600" />
    case "Pending":
      return <Clock className="w-4 h-4 text-yellow-600" />
    case "Failed":
      return <XCircle className="w-4 h-4 text-red-600" />
    default:
      return <AlertCircle className="w-4 h-4 text-gray-600" />
  }
}

const getStatusVariant = (status) => {
  switch (status) {
    case "Success":
      return "default"
    case "Pending":
      return "secondary"
    case "Failed":
      return "destructive"
    default:
      return "outline"
  }
}

export function StatusContent() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">Status Enquiry</h1>
        <p className="text-muted-foreground">Check the status of your transactions and bookings</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Total Enquiries</CardTitle>
            <Search className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">2,456</div>
            <p className="text-xs text-muted-foreground">This month</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Successful</CardTitle>
            <CheckCircle className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">2,234</div>
            <p className="text-xs text-muted-foreground">91% success rate</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Pending</CardTitle>
            <Clock className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">156</div>
            <p className="text-xs text-muted-foreground">In process</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Failed</CardTitle>
            <XCircle className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">66</div>
            <p className="text-xs text-muted-foreground">2.7% failure rate</p>
          </CardContent>
        </Card>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Check Transaction Status</CardTitle>
            <CardDescription>Enter transaction details to check status</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="search-type">Search By</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select search type" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="transaction-id">Transaction ID</SelectItem>
                  <SelectItem value="reference-no">Reference Number</SelectItem>
                  <SelectItem value="mobile-no">Mobile Number</SelectItem>
                  <SelectItem value="order-id">Order ID</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="search-value">Search Value</Label>
              <Input id="search-value" placeholder="Enter transaction ID, reference number, etc." />
            </div>
            <div className="space-y-2">
              <Label htmlFor="service-type">Service Type (Optional)</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select service type" />
                </SelectTrigger>
                <SelectContent>
                  {enquiryTypes.map((type) => (
                    <SelectItem key={type} value={type.toLowerCase().replace(/\s+/g, "-")}>
                      {type}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="date-range">Date Range</Label>
              <div className="grid grid-cols-2 gap-2">
                <Input id="from-date" type="date" />
                <Input id="to-date" type="date" />
              </div>
            </div>
            <Button className="w-full">
              <Search className="w-4 h-4 mr-2" />
              Check Status
            </Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Recent Enquiries</CardTitle>
            <CardDescription>Your recent status enquiries</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentEnquiries.map((enquiry) => (
                <div key={enquiry.id} className="p-3 border rounded-lg space-y-2">
                  <div className="flex items-center justify-between">
                    <span className="font-medium">{enquiry.id}</span>
                    <div className="flex items-center gap-2">
                      {getStatusIcon(enquiry.status)}
                      <Badge variant={getStatusVariant(enquiry.status)}>{enquiry.status}</Badge>
                    </div>
                  </div>
                  <div className="text-sm text-muted-foreground">
                    <p>
                      {enquiry.type} • {enquiry.amount}
                    </p>
                    <p>Ref: {enquiry.refNo}</p>
                    <p>{enquiry.date}</p>
                  </div>
                  <Button size="sm" variant="outline" className="w-full bg-transparent">
                    View Details
                  </Button>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Status Definitions</CardTitle>
          <CardDescription>Understanding transaction statuses</CardDescription>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div className="p-4 border rounded-lg">
              <div className="flex items-center gap-2 mb-2">
                <CheckCircle className="w-5 h-5 text-green-600" />
                <h3 className="font-medium">Success</h3>
              </div>
              <p className="text-sm text-muted-foreground">Transaction completed successfully</p>
            </div>
            <div className="p-4 border rounded-lg">
              <div className="flex items-center gap-2 mb-2">
                <Clock className="w-5 h-5 text-yellow-600" />
                <h3 className="font-medium">Pending</h3>
              </div>
              <p className="text-sm text-muted-foreground">Transaction is being processed</p>
            </div>
            <div className="p-4 border rounded-lg">
              <div className="flex items-center gap-2 mb-2">
                <XCircle className="w-5 h-5 text-red-600" />
                <h3 className="font-medium">Failed</h3>
              </div>
              <p className="text-sm text-muted-foreground">Transaction failed or was declined</p>
            </div>
            <div className="p-4 border rounded-lg">
              <div className="flex items-center gap-2 mb-2">
                <AlertCircle className="w-5 h-5 text-blue-600" />
                <h3 className="font-medium">Refunded</h3>
              </div>
              <p className="text-sm text-muted-foreground">Amount has been refunded</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </motion.div>
  )
}
