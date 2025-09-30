"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Badge } from "@/components/ui/badge"
import { Building, Receipt, FileText, CreditCard } from "lucide-react"

const municipalityServices = [
  { title: "Property Tax", description: "Pay annual property tax", icon: Building },
  { title: "Water Bill", description: "Municipal water bill payment", icon: Receipt },
  { title: "Trade License", description: "Trade license fees and renewals", icon: FileText },
  { title: "Other Fees", description: "Miscellaneous municipal fees", icon: CreditCard },
]

const municipalities = [
  "Mumbai Municipal Corporation",
  "Delhi Municipal Corporation",
  "Bangalore City Corporation",
  "Chennai Corporation",
  "Kolkata Municipal Corporation",
  "Pune Municipal Corporation",
  "Hyderabad Municipal Corporation",
  "Ahmedabad Municipal Corporation",
]

const recentPayments = [
  {
    id: "MUN001",
    type: "Property Tax",
    municipality: "Mumbai Municipal Corporation",
    amount: "$450",
    status: "Paid",
    date: "2024-01-15",
  },
  {
    id: "MUN002",
    type: "Water Bill",
    municipality: "Delhi Municipal Corporation",
    amount: "$85",
    status: "Pending",
    date: "2024-01-14",
  },
  {
    id: "MUN003",
    type: "Trade License",
    municipality: "Bangalore City Corporation",
    amount: "$200",
    status: "Paid",
    date: "2024-01-13",
  },
]

export function MunicipalityContent() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">Municipality Payment</h1>
        <p className="text-muted-foreground">Pay municipal taxes, bills, and fees online</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {municipalityServices.map((service, index) => (
          <motion.div
            key={service.title}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: index * 0.1 }}
          >
            <Card className="hover:shadow-lg transition-shadow cursor-pointer">
              <CardHeader className="text-center">
                <service.icon className="w-12 h-12 text-primary mx-auto mb-2" />
                <CardTitle className="text-lg">{service.title}</CardTitle>
                <CardDescription>{service.description}</CardDescription>
              </CardHeader>
            </Card>
          </motion.div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Make Municipal Payment</CardTitle>
            <CardDescription>Pay municipal taxes and fees</CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="municipality">Municipality</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select municipality" />
                </SelectTrigger>
                <SelectContent>
                  {municipalities.map((municipality) => (
                    <SelectItem key={municipality} value={municipality.toLowerCase().replace(/\s+/g, "-")}>
                      {municipality}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="service-type">Service Type</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select service type" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="property-tax">Property Tax</SelectItem>
                  <SelectItem value="water-bill">Water Bill</SelectItem>
                  <SelectItem value="trade-license">Trade License</SelectItem>
                  <SelectItem value="other-fees">Other Fees</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="property-id">Property/Account ID</Label>
              <Input id="property-id" placeholder="Enter property or account ID" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="assessment-year">Assessment Year</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Select year" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="2024-25">2024-25</SelectItem>
                  <SelectItem value="2023-24">2023-24</SelectItem>
                  <SelectItem value="2022-23">2022-23</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <Label htmlFor="amount">Amount</Label>
              <Input id="amount" placeholder="Enter amount" />
            </div>
            <div className="space-y-2">
              <Label htmlFor="mobile">Mobile Number</Label>
              <Input id="mobile" placeholder="Enter mobile number" />
            </div>
            <Button className="w-full">Fetch Bill & Pay</Button>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Recent Payments</CardTitle>
            <CardDescription>Your recent municipal payments</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentPayments.map((payment) => (
                <div key={payment.id} className="flex items-center justify-between p-3 border rounded-lg">
                  <div>
                    <p className="font-medium">{payment.type}</p>
                    <p className="text-sm text-muted-foreground">{payment.municipality}</p>
                    <p className="text-xs text-muted-foreground">{payment.date}</p>
                  </div>
                  <div className="text-right">
                    <p className="font-medium">{payment.amount}</p>
                    <Badge variant={payment.status === "Paid" ? "default" : "secondary"}>{payment.status}</Badge>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Important Information</CardTitle>
          <CardDescription>Things to know about municipal payments</CardDescription>
        </CardHeader>
        <CardContent>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div className="space-y-4">
              <h3 className="font-medium">Property Tax</h3>
              <ul className="text-sm text-muted-foreground space-y-2">
                <li>• Annual tax based on property value</li>
                <li>• Due dates vary by municipality</li>
                <li>• Penalty for late payment</li>
                <li>• Discounts for early payment available</li>
              </ul>
            </div>
            <div className="space-y-4">
              <h3 className="font-medium">Water Bill</h3>
              <ul className="text-sm text-muted-foreground space-y-2">
                <li>• Monthly or quarterly billing</li>
                <li>• Based on meter reading</li>
                <li>• Fixed charges plus usage charges</li>
                <li>• Disconnection for non-payment</li>
              </ul>
            </div>
            <div className="space-y-4">
              <h3 className="font-medium">Trade License</h3>
              <ul className="text-sm text-muted-foreground space-y-2">
                <li>• Required for business operations</li>
                <li>• Annual renewal mandatory</li>
                <li>• Fees vary by business type</li>
                <li>• Penalties for operating without license</li>
              </ul>
            </div>
            <div className="space-y-4">
              <h3 className="font-medium">Payment Tips</h3>
              <ul className="text-sm text-muted-foreground space-y-2">
                <li>• Keep property documents ready</li>
                <li>• Check for any pending dues</li>
                <li>• Save payment receipts</li>
                <li>• Set reminders for due dates</li>
              </ul>
            </div>
          </div>
        </CardContent>
      </Card>
    </motion.div>
  )
}
