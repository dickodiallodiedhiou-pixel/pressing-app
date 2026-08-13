import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ServiceService {
  private apiUrl = 'http://127.0.0.1:8000/api/services';

  constructor(private http: HttpClient) {}

  getServices(): Observable<any> {
    return this.http.get(this.apiUrl);
  }

  createService(data: any): Observable<any> {
    return this.http.post(this.apiUrl, data);
  }

  updateService(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, data);
  }

  deleteService(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }
}