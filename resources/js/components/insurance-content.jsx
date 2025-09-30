"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Badge } from "@/components/ui/badge"
import { Shield, Car, Home, Heart, Users } from "lucide-react"

const insuranceTypes = [
  { icon: Car, title: "Vehicle Insurance", description: "Car, bike, and commercial vehicle insurance", count: "1,234" },
  { icon: Heart, title: "Health Insurance", description: "Medical and health insurance premiums", count: "856" },
  { icon: Home, title: "Home Insurance", description: "Property and home insurance payments", count: "432" },
  { icon: Users, title: "Life Insurance", description: "Life insurance premium payments", count: "298" },
]

const recentPayments = [
  { id: "INS001", type: "Vehicle", company: "HDFC ERGO", amount: "$450", dueDate: "2024-01-25", status: "Paid" },
  { id: "INS002", type: "Health", company: "Star Health", amount: "$320", dueDate: "2024-01-28", status: "Pending" },
  { id: "INS003", type: "Life", company: "LIC", amount: "$280", dueDate: "2024-01-20", status: "Overdue" },
]

const insuranceCompanies = [
  "HDFC ERGO",
  "ICICI Lombard",
  "Bajaj Allianz",
  "Star Health",
  "LIC",
  "SBI General",
  "New India Assurance",
  "Oriental Insurance",
]

export function InsuranceContent() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">Insurance Payment</h1>
        <p className="text-muted-foreground">Pay insurance premiums for various policies</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {insuranceTypes.map((type, index) => (
          <motion.div
            key={type.title}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: index * 0.1 }}
          >
            <Card className="hover:shadow-lg transition-shadow">
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">{type.title}</CardTitle>
                <type.icon className="h-4 w-4 text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{type.count}</div>
                <p className="text-xs text-muted-foreground">{type.description}</p>
              </CardContent>
            </Card>
          </motion.div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Pay Insurance Premium</CardTitle>
            <CardDescription>Enter policy details to pay premium</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="insurance-type">Insurance Type</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select insurance type" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="vehicle">Vehicle Insurance</SelectItem>
                  <SelectItem value="health">Health Insurance</SelectItem>
                  <SelectItem value="home">Home Insurance</SelectItem>
                  <SelectItem value="life">Life Insurance</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="company">Insurance Company</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select company" />
                </SelectTrigger>
                <SelectContent>
                  {insuranceCompanies.map((company) => (
                    <SelectItem key={company} value={company.toLowerCase().replace(/\s+/g, "-")}>
                      {company}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="policy-no">Policy Number</Label>
              <Input id="policy-no" placeholder="Enter policy number" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="premium-amount">Premium Amount</Label>
              <Input id="premium-amount" placeholder="Enter premium amount" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="mobile">Mobile Number</Label>
              <Input id="mobile" placeholder="Enter mobile number" />
            </div>
            <Button className="w-full">Pay Premium</Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Recent Premium Payments</CardTitle>
            <CardDescription>Your recent insurance premium payments</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentPayments.map((payment) => (
                <div key={payment.id} className="flex items-center justify-between p-3 border rounded-lg">
                  <div>
                    <p className="font-medium">{payment.id}</p>
                    <p className="text-sm text-muted-foreground">
                      {payment.type} • {payment.company}
                    </p>
                    <p className="text-xs text-muted-foreground">Due: {payment.dueDate}</p>
                  </div>
                  <div className="text-right">
                    <p className="font-medium">{payment.amount}</p>
                    <Badge
                      variant={
                        payment.status === "Paid"
                          ? "default"
                          : payment.status === "Pending"
                            ? "secondary"
                            : "destructive"
                      }
                    >
                      {payment.status}
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
          <CardTitle>Policy Reminders</CardTitle>
          <CardDescription>Upcoming premium due dates</CardDescription>
        </CardHeader>
        <CardContent>
          <div className="space-y-3">
            <div className="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
              <div className="flex items-center gap-3">
                <Shield className="w-5 h-5 text-yellow-600" />
                <div>
                  <p className="font-medium">Vehicle Insurance - HDFC ERGO</p>
                  <p className="text-sm text-muted-foreground">Policy: VEH123456789</p>
                </div>
              </div>
              <div className="text-right">
                <p className="font-medium">$450</p>
                <p className="text-sm text-yellow-600">Due in 3 days</p>
              </div>
            </div>
            <div className="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
              <div className="flex items-center gap-3">
                <Heart className="w-5 h-5 text-red-600" />
                <div>
                  <p className="font-medium">Health Insurance - Star Health</p>
                  <p className="text-sm text-muted-foreground">Policy: HLT987654321</p>
                </div>
              </div>
              <div className="text-right">
                <p className="font-medium">$320</p>
                <p className="text-sm text-red-600">Overdue</p>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>
    </motion.div>
  )
}
