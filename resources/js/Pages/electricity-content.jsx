"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Badge } from "@/components/ui/badge"
import { Lightbulb, Zap, Receipt, AlertCircle } from "lucide-react"

const electricityProviders = [
  { name: "State Electricity Board", code: "SEB", region: "State Wide" },
  { name: "Delhi Electricity Board", code: "DEB", region: "Delhi" },
  { name: "Mumbai Power", code: "MP", region: "Mumbai" },
  { name: "Bangalore Electricity", code: "BE", region: "Bangalore" },
  { name: "Chennai Power", code: "CP", region: "Chennai" },
]

const recentBills = [
  { id: "ELEC001", provider: "SEB", amount: "$85", dueDate: "2024-01-25", status: "Paid", consumerNo: "123456789" },
  { id: "ELEC002", provider: "DEB", amount: "$92", dueDate: "2024-01-28", status: "Pending", consumerNo: "987654321" },
  { id: "ELEC003", provider: "MP", amount: "$78", dueDate: "2024-01-20", status: "Overdue", consumerNo: "456789123" },
]

export function ElectricityContent() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">Electricity Bill Payment</h1>
        <p className="text-muted-foreground">Pay electricity bills for various providers</p>
      </div>

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

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Pay Electricity Bill</CardTitle>
            <CardDescription>Enter your consumer details to pay bill</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="provider">Electricity Provider</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select provider" />
                </SelectTrigger>
                <SelectContent>
                  {electricityProviders.map((provider) => (
                    <SelectItem key={provider.code} value={provider.code}>
                      {provider.name} ({provider.region})
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="consumer-no">Consumer Number</Label>
              <Input id="consumer-no" placeholder="Enter consumer number" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="amount">Amount</Label>
              <Input id="amount" placeholder="Enter amount" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="mobile">Mobile Number</Label>
              <Input id="mobile" placeholder="Enter mobile number for confirmation" />
            </div>
            <Button className="w-full">Fetch Bill & Pay</Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Recent Bill Payments</CardTitle>
            <CardDescription>Your recent electricity bill payments</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentBills.map((bill) => (
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
                        bill.status === "Paid" ? "default" : bill.status === "Pending" ? "secondary" : "destructive"
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

      <Card>
        <CardHeader>
          <CardTitle>Available Electricity Providers</CardTitle>
          <CardDescription>List of supported electricity providers</CardDescription>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {electricityProviders.map((provider) => (
              <div key={provider.code} className="p-4 border rounded-lg">
                <h3 className="font-medium">{provider.name}</h3>
                <p className="text-sm text-muted-foreground">Code: {provider.code}</p>
                <p className="text-sm text-muted-foreground">Region: {provider.region}</p>
              </div>
            ))}
          </div>
        </CardContent>
      </Card>
    </motion.div>
  )
}
