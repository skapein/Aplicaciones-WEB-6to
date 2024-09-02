import { Component, OnInit } from '@angular/core';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { IFactura } from '../Interfaces/factura';
import { Router, RouterLink } from '@angular/router';
import { FacturaService } from '../Services/factura.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-facturas',
  standalone: true,
  imports: [SharedModule, RouterLink],
  templateUrl: './facturas.component.html',
  styleUrls: ['./facturas.component.scss']
})
export class FacturasComponent implements OnInit {
  listafacturas: IFactura[] = [];

  constructor(private facturaServicio: FacturaService, private router: Router) {}

  ngOnInit(): void {
    this.cargarFacturas();
  }

  cargarFacturas(): void {
    this.facturaServicio.todos().subscribe(
      (data: IFactura[]) => {
        this.listafacturas = data;
      },
      error => {
        console.error('Error al cargar las facturas:', error);
      }
    );
  }

  eliminar(idFactura: number): void {
    Swal.fire({
      title: 'Eliminar Factura',
      text: '¿Está seguro de eliminar esta factura?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#f00',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Eliminar'
    }).then((result) => {
      if (result.isConfirmed) {
        this.facturaServicio.eliminar(idFactura).subscribe(
          () => {
            Swal.fire('Factura eliminada', 'La factura ha sido eliminada correctamente.', 'success');
            this.cargarFacturas(); // Refrescar la lista de facturas
          },
          error => {
            console.error('Error al eliminar la factura:', error);
            Swal.fire('Error', 'Hubo un problema al eliminar la factura.', 'error');
          }
        );
      }
    });
  }

  actualizar(idFactura: number): void {
   
    this.router.navigate(['/facturas/editar', idFactura]);
  }
}
